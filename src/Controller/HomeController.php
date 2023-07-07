<?php

namespace App\Controller;

use App\Service\CallApiService;
use App\Service\OffreApiService;
use App\Service\MessageApiService;
use App\Repository\OffreRepository;
use App\Service\DestinationApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomeController extends AbstractController
{


    #[Route('/', name: 'app_home')]
    public function index(DestinationApiService $destinationApiService,OffreApiService $offreApiService,OffreRepository $offreRepository): Response
    {
            $offreNOBcoupCoeur=$offreRepository->getOffresNoBcoupCoeur();
            $offreBcoupCoeur=$offreRepository->getOffresBcoupCoeur();
             return $this->render('home/index.html.twig', [
            'destinations' => $destinationApiService->getDestinations(),
            'controller_name' => 'HomeController',
            'offres' => $offreBcoupCoeur,
            'offresNoBcoupCoeur'=> $offreNOBcoupCoeur,
        ]);
    }
}
