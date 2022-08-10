<?php

namespace App\Controller;

use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/finder', name: 'app_finder_')]
class FinderController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();

        return $this->render('finder/index.html.twig', ['movies' => $movies]);
    }

    #[Route('/{id}/like', name: 'like')]
    public function likeProject(Like $finder, LikeRepository $finderRepository): Response
    {
        $finder->setLikeStatus(true);
        $finderRepository->add($finder, true);

        return $this->redirectToRoute('app_finder_index');
    }

    #[Route('/{id}/dislike', name: 'dislike')]
    public function dislikeProject(Like $finder, LikeRepository $finderRepository): Response
    {
        $finder->setLikeStatus(false);
        $finderRepository->add($finder, true);

        return $this->redirectToRoute('app_finder_index');
    }
}
