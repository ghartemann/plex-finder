<?php

namespace App\Controller;

use App\Repository\WatchlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FinderController extends AbstractController
{
    #[Route('/finder', name: 'app_finder')]
    public function index(WatchlistRepository $watchlistRepository): Response
    {
        $movies = $watchlistRepository->findAll();

        return $this->render('finder/index.html.twig', ['movies' => $movies]);
    }
}
