<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/watchlist', name: 'app_watchlist_')]
class WatchlistController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(MovieRepository $movieRepository,): Response
    {
        //fetching all movies
        $movies = $movieRepository->findAll();

        return $this->render('watchlist/index.html.twig', ['movies' => $movies]);
    }

    #[Route('/{slug}', name: 'show')]
    public function show(Movie $movie): Response
    {
        return $this->render('watchlist/show.html.twig', ['movie' => $movie]);
    }
}
