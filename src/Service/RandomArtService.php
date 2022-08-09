<?php

namespace App\Service;

use App\Repository\WatchlistRepository;

class RandomArtService
{
    private WatchlistRepository $watchlistRepository;

    public function __construct(WatchlistRepository $watchlistRepository)
    {
        $this->watchlistRepository = $watchlistRepository;
    }

    public function getRandomArt(): string
    {
        $movies = $this->watchlistRepository->findAll();
        $banners = [];
        $banner = '';

        foreach ($movies as $movie) {
            $banners[] = $movie->getBanner();
        }

        if ($banners) {
            $key = array_rand($banners);
            $banner = $banners[$key];
        }

        return $banner;
    }
}
