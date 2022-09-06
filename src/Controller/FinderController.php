<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use App\Repository\TasteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

#[IsGranted('ROLE_USER')]
#[Route('/finder', name: 'app_finder_')]
class FinderController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(TasteRepository $tasteRepository): Response
    {
        $tastes = $tasteRepository->findAll();

        return $this->render('finder/index.html.twig', ['tastes' => $tastes]);
    }

    #[Route('/{id}/like', name: 'like', methods: ['POST'])]
    public function likeAjax(
        TasteRepository $tasteRepository,
        Request         $request
    ): Response
    {
        $movieId = $request->get('id');

        $like = $request->getContent() === 'true';
        $taste = $tasteRepository->findOneBy(['movie' => $movieId]);
        $taste->setTasteStatus($like);
        $tasteRepository->add($taste, true);
        return new JsonResponse();
    }

    #[Route('/reset', name: 'reset')]
    public function reset(TasteRepository $tasteRepository): Response
    {
        $tastes = $tasteRepository->findBy(['user' => $this->getUser()->getId()]);

        foreach ($tastes as $taste) {
            $taste->setTasteStatus(null);
            $tasteRepository->add($taste, true);
        }

        return $this->redirectToRoute('app_finder_index');
    }

    #[Route('/matches', name: 'matches')]
    public function showMatches(TasteRepository $tasteRepository, MovieRepository $movieRepository): Response
    {
        $tastes = $tasteRepository->findBy(['tasteStatus' => true]);
        dd($tastes);
        
        foreach ($tastes as $taste) {
            $movies[] = $movieRepository->findOneBy(['title' => $taste->getMovie()->getTitle()]);
        }
//
//        $unique = array_unique($movies, 0);
//
//        $duplicates = array_diff_assoc($movies, $unique);
//
//        print_r($duplicates);

        return $this->render('watchlist/index.html.twig', ['movies' => $movies]);
    }
}
