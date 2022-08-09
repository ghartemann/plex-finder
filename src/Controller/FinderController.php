<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FinderController extends AbstractController
{
    #[Route('/finder', name: 'app_finder')]
    public function index(): Response
    {
        return $this->render('finder/index.html.twig', [
            'controller_name' => 'FinderController',
        ]);
    }
}
