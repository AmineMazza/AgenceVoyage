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


    #[Route('/home', name: 'app_home')]
    public function index(OffreApiService $offreApiService,OffreRepository $offreRepository): Response
    {
<<<<<<< HEAD
            $offreNOBcoupCoeur=$offreRepository->getOffresNoBcoupCoeur();
            $offreBcoupCoeur=$offreRepository->getOffresBcoupCoeur();
         return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'offres' => $offreBcoupCoeur,
            'offresNoBcoupCoeur'=> $offreNOBcoupCoeur,
=======

        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'offres' => $offreApiService->getOffres(),
>>>>>>> 32d7a98df5eec1fdb61d50d5c90a585c871feb57
        ]);
    }
}
