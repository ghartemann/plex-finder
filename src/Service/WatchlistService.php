<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WatchlistService
{
    // TODO: move this elsewhere ASAP
    public const TOKEN = 'zDyYfQtN_jfYqq_JPbiB';

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getWatchlist(): array
    {
        // TODO: not the best way to do this but it's more readable this way
        $response = $this->client->request(
            'GET', 'https://metadata.provider.plex.tv/library/sections/watchlist/all?&includeFields=' .
            'ratingKey%2C' .
            'title%2C' .
            'year%2C' .
            'duration%2C' .
            'thumb%2C' .
            'rating' .
            '&includeElements=Guid&sort=watchlistedAt%3Adesc&type=1&X-Plex-Token=' . self::TOKEN . '&x-plex-container-size=200');
        $statusCode = $response->getStatusCode();

        $data = [];

        if ($statusCode == '200') {
            $dataFromAPI = $response->getContent();
            $xmlData = simplexml_load_string($dataFromAPI);
            $jsonData = json_encode($xmlData);
            $data = json_decode($jsonData, true);
        }

        return $data;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    // get info missing from the simple watchlist request (summary)
    public function getMissingInfo($ratingKey): array
    {
        $response = $this->client->request('GET', 'https://metadata.provider.plex.tv/library/metadata/' . $ratingKey . '?X-Plex-Token=' . self::TOKEN . '&x-plex-container-size=200');
        $xmlData = $response->getContent();
        $xmlData = simplexml_load_string($xmlData);
        $jsonData = json_encode($xmlData);
        $missingInfo = json_decode($jsonData, true);

        return $missingInfo;
    }
}
