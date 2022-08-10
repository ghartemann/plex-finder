<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\WatchlistService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ImportController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/import', name: 'app_import')]
    public function import(
        MovieRepository  $movieRepository,
        WatchlistService $watchlistService
    ): Response
    {
        // fetching data from service
        $data = $watchlistService->getWatchlist();

        foreach ($data["Video"] as $dataElement) {
            if ($movieRepository->findOneBy(['title' => $dataElement['@attributes']['title']]) == null) {
                // get missing info
                $missingInfo = $watchlistService->getMissingInfo($dataElement['@attributes']['ratingKey']);

                $directorNames = [];
                foreach ($missingInfo['Video']['Director'] as $director) {
                    if (isset($director['tag']))
                        $directorNames[] = $director['tag'];
                }

                $directorNameList = implode(', ', $directorNames);

                $movie = new Movie();
                $movie
                    ->setTitle($dataElement['@attributes']['title'])
                    ->setYear($dataElement['@attributes']['year'])
                    ->setDuration($dataElement['@attributes']['duration'])
                    ->setThumbnail($dataElement['@attributes']['thumb'])
                    ->setSummary($missingInfo["Video"]['@attributes']['summary'])
                    ->setDirector($directorNameList)
                    ->setSlug($missingInfo['Video']['@attributes']['slug'])
                    ->setPlexLink($missingInfo['Video']['@attributes']['publicPagesURL'])
                    ->setStudio($missingInfo['Video']['@attributes']['studio']);

                if (isset($dataElement['@attributes']['rating']))
                    $movie->setRating($dataElement['@attributes']['rating']);

                if (isset($missingInfo['Video']['@attributes']['banner']))
                    $movie->setBanner($missingInfo['Video']['@attributes']['art']);

                if (isset($missingInfo['Video']['@attributes']['tagline']))
                    $movie->setTagline($missingInfo['Video']['@attributes']['tagline']);

                // TODO: this doesn't work
                if (isset($missingInfo['Video']['@attributes']['originaltitle'])) {
                    $movie->setOriginalTitle($missingInfo['Video']['@attributes']['original_title']);
                }

                $movieRepository->add($movie, true);
            }
        }

        // hide entries that aren't in Plex movie any more
        $movies = $movieRepository->findAll();

        $moviesFromAPI = $watchlistService->getWatchlist();
        $moviesTitles = [];

        foreach ($moviesFromAPI['Video'] as $movieSingular) {
            $moviesTitles[] = $movieSingular['@attributes']['title'];
        }

        foreach ($movies as $movie) {
            if (!in_array($movie->getTitle(), $moviesTitles)) {
                $movie = $movieRepository->findOneBy(['title' => $movie->getTitle()]);
                $movie->setStatus(false);
                $movieRepository->add($movie, true);
            }
        }

        return $this->redirectToRoute('app_watchlist_index');
    }
}
