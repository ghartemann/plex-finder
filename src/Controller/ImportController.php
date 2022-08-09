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
    public function index(
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

                $watchlist = new Watchlist();
                $watchlist
                    ->setTitle($dataElement['@attributes']['title'])
                    ->setYear($dataElement['@attributes']['year'])
                    ->setDuration($dataElement['@attributes']['duration'])
                    ->setRating($dataElement['@attributes']['rating'])
                    ->setThumbnail($dataElement['@attributes']['thumb'])
                    ->setSummary($missingInfo["Video"]['@attributes']['summary']);
                $watchlistRepository->add($watchlist, true);
            }
        }

        $movies = $watchlistRepository->findAll();

        return $this->render('import/index.html.twig', ['movies' => $movies]);
    }
}
