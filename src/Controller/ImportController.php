<?php

namespace App\Controller;

use App\Entity\Watchlist;
use App\Repository\WatchlistRepository;
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
        WatchlistRepository $watchlistRepository,
        WatchlistService    $watchlistService
    ): Response
    {
        // fetching data from service
        $data = $watchlistService->getWatchlist();

        foreach ($data["Video"] as $dataElement) {
            if ($watchlistRepository->findOneBy(['title' => $dataElement['@attributes']['title']]) == null) {
                // get missing info
                $missingInfo = $watchlistService->getMissingInfo($dataElement['@attributes']['ratingKey']);

                $directorNames = [];
                foreach ($missingInfo['Video']['Director'] as $director) {
                    $directorNames[] = $director['tag'];
                }

                $directorNameList = implode(', ', $directorNames);

                $watchlist = new Watchlist();
                $watchlist
                    ->setTitle($dataElement['@attributes']['title'])
                    ->setYear($dataElement['@attributes']['year'])
                    ->setDuration($dataElement['@attributes']['duration'])
                    ->setRating($dataElement['@attributes']['rating'])
                    ->setThumbnail($dataElement['@attributes']['thumb'])
                    ->setSummary($missingInfo["Video"]['@attributes']['summary'])
                    ->setDirector($directorNameList)
                    ->setSlug($missingInfo['Video']['@attributes']['slug'])
                    ->setStudio($missingInfo['Video']['@attributes']['studio']);

                if (isset($missingInfo['Video']['@attributes']['tagline']))
                    $watchlist->setTagline($missingInfo['Video']['@attributes']['tagline']);

                // TODO: this doesn't work
                if (isset($missingInfo['Video']['@attributes']['originaltitle'])) {
                    $watchlist->setOriginalTitle($missingInfo['Video']['@attributes']['original_title']);
                }

                $watchlistRepository->add($watchlist, true);
            }
        }

        // hide entries that aren't in Plex watchlist any more
        $movies = $watchlistRepository->findAll();

        $moviesFromAPI = $watchlistService->getWatchlist();
        $moviesTitles = [];
        
        foreach ($moviesFromAPI['Video'] as $movieSingular) {
            $moviesTitles[] = $movieSingular['@attributes']['title'];
        }

        foreach ($movies as $movie) {
            if (!in_array($movie->getTitle(), $moviesTitles)) {
                $watchlist = $watchlistRepository->findOneBy(['title' => $movie->getTitle()]);
                $watchlist->setStatus(false);
                $watchlistRepository->add($watchlist, true);
            }
        }

        return $this->redirectToRoute('app_watchlist_index');
    }
}
