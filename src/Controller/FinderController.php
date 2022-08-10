<?php

namespace App\Controller;

use App\Entity\Taste;
use App\Repository\TasteRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/finder', name: 'app_finder_')]
class FinderController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();

        return $this->render('finder/index.html.twig', ['movies' => $movies]);
    }

    #[Route('/{id}/like', name: 'like', methods: ['POST'])]
    public function likeAjax(
        TasteRepository $tasteRepository,
        MovieRepository $movieRepository,
        Request         $request
    ): Response
    {
        $movieId = $request->query->get('id');
        dd($movieId);
        $movie = $movieRepository->findOneBy(['id' => $movieId]);

        $like = $request->getContent() === 'true';
        $taste = new Taste();
        $taste
            ->setTasteStatus($like)
            ->setMovie($movie)
            ->setUser($this->getUser());
        $tasteRepository->add($taste, true);
        return new JsonResponse();
    }
}
