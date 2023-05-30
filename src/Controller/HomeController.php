<?php

namespace App\Controller;

use App\Repository\OffreRepository;
use App\Service\CallApiService;
use App\Service\MessageApiService;
use App\Service\OffreApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomeController extends AbstractController
{


    #[Route('/', name: 'app_home')]
    public function index(OffreApiService $offreApiService,OffreRepository $offreRepository): Response
    {
            $offreNOBcoupCoeur=$offreRepository->getOffresNoBcoupCoeur();
            $offreBcoupCoeur=$offreRepository->getOffresBcoupCoeur();
             return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'offres' => $offreBcoupCoeur,
            'offresNoBcoupCoeur'=> $offreNOBcoupCoeur,
        ]);
    }
}
