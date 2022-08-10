<?php

namespace App\Service;

use App\Repository\MovieRepository;

class RandomArtService
{
    private MovieRepository $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getRandomArt(): string
    {
        $movies = $this->movieRepository->findAll();
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
