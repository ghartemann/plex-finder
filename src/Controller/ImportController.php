<?php

namespace App\Controller;

use App\Entity\Watchlist;
use App\Repository\WatchlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImportController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/import', name: 'app_import')]
    public function index(HttpClientInterface $client, WatchlistRepository $watchlistRepository): Response
    {
        $response = $client->request('GET', 'https://metadata.provider.plex.tv/library/sections/watchlist/all?&includeFields=ratingKey%2Ctitle%2Cyear%2Cduration%2Cthumb%2Crating&includeElements=Guid&sort=watchlistedAt%3Adesc&type=1&X-Plex-Token=zDyYfQtN_jfYqq_JPbiB');
        $statusCode = $response->getStatusCode();

        $data = [];

        if ($statusCode == "200") {
            $xmlData = $response->getContent();
            $xmlData = simplexml_load_string($xmlData);
            $jsonData = json_encode($xmlData);
            $data = json_decode($jsonData, true);
        }

        foreach ($data["Video"] as $dataElement) {
            if ($watchlistRepository->findOneBy(['title' => $dataElement['@attributes']['title']]) == null) {
                $summary = $client->request('GET', 'https://metadata.provider.plex.tv/library/metadata/' . $dataElement['@attributes']['ratingKey'] . '?X-Plex-Token=zDyYfQtN_jfYqq_JPbiB');
                $xmlData2 = $summary->getContent();
                $xmlData2 = simplexml_load_string($xmlData2);
                $jsonData2 = json_encode($xmlData2);
                $data2 = json_decode($jsonData2, true);

                $watchlist = new Watchlist();
                $watchlist
                    ->setTitle($dataElement['@attributes']['title'])
                    ->setYear($dataElement['@attributes']['year'])
                    ->setDuration($dataElement['@attributes']['duration'])
                    ->setRating($dataElement['@attributes']['rating'])
                    ->setThumbnail($dataElement['@attributes']['thumb'])
                    ->setSummary($data2["Video"]['@attributes']['summary']);
                $watchlistRepository->add($watchlist, true);
            }
        }

        return $this->render('import/index.html.twig', ['data' => $data, 'statusCode' => $statusCode]);
    }
}
