<?php

namespace App\Controller;

use App\Entity\Watchlist;
use App\Repository\WatchlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/watchlist', name: 'app_watchlist_')]
class WatchlistController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(WatchlistRepository $watchlistRepository,): Response
    {
        //fetching all movies from watchlist
        $movies = $watchlistRepository->findAll();

        return $this->render('watchlist/index.html.twig', ['movies' => $movies]);
    }

    #[Route('/{slug}', name: 'show')]
    public function show(Watchlist $watchlist): Response
    {
        return $this->render('watchlist/show.html.twig', ['movie' => $watchlist]);
    }
}
