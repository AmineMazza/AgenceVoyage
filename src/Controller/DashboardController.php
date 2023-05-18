<?php

namespace App\Controller;

use App\Entity\Commercial;
use App\Entity\Offre;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\AgentRepository;
use App\Repository\CommercialRepository;
use App\Repository\MessageRepository;
use App\Repository\OffreRepository;
use App\Repository\ReservationRepository;
use App\Service\CommercialApiService;
use App\Service\OffreApiService;
use App\Service\ReservationApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(OffreRepository $offreRepository,AgentRepository $agentRepository,CommercialRepository $commercialRepository,MessageRepository $messageRepository,ReservationRepository $reservationRepository): Response
    {
        $offres=$offreRepository->countOffre();
        $agent=$agentRepository->countAgent();
        $messages=$messageRepository->countMessage();
        $commercial=$commercialRepository->countCommercial();
        $reservation=$reservationRepository->countReservation();
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'offres'=> $offres,
            'agent'=>$agent,
            'messages'=>$messages,
            'commercial'=>$commercial,
            'reservation'=>$reservation,
        ]);
    }

    #[Route('/myOffres', name: 'app_myoffres')]
    public function myoffres(OffreApiService $offreApiService): Response
    {
        return $this->render('dashboard/myoffres.html.twig', [
            'offres' => $offreApiService->getOffresParams(['id_user'=>$this->getUser()->getId()]),
        ]);
    }

    #[Route('/myOffres/{idO}/reserver', name: 'app_myoffres_reserver', methods: ['GET', 'POST'])]
    public function reserver(Request $request ,array $_route_params , ReservationApiService $ReservationApiService, CommercialApiService $commercialApiService, OffreApiService $offreApiService): Response
    {
        $offre = $offreApiService->getOffre($_route_params['idO']);
        // dd($offre);
        if(!$this->isGranted("ROLE_ADMIN")){
            if ($offre->getIdUser()->getId() != $this->getUser()->getId()) {
                return $this->redirectToRoute('app_myoffres', [], Response::HTTP_SEE_OTHER);
            }
        }
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $extraFields = $form->getExtraData();
            
            // dd($extraFields);
            if($extraFields != []){
                if($extraFields['isCommercial'] == 'on'){
                    $commercial = new Commercial();
                    if($extraFields['SelectMethod'] == 'new') {
                        $commercial->setNom($extraFields['id_commercial']['nom']);
                        $commercial->setPrenom($extraFields['id_commercial']['prenom']);
                        $commercial->setTelephone($extraFields['id_commercial']['telephone']);
                        $commercial->setAdresse($extraFields['id_commercial']['adresse']);
                    }
                    elseif($extraFields['SelectMethod'] == 'exist') {
                        $commercial =  $commercialApiService->getCommercial($extraFields['id_commercial']);
                    }
                    $reservation->setIdCommercial($commercial);
                }
            }
            // dd($reservation);
            $idR = $ReservationApiService->AddReservation($reservation, $_route_params['idO']);

            return $this->redirectToRoute('app_voyageur_index', ['idR' => $idR], Response::HTTP_SEE_OTHER);
        }
        $choices = [];
        if($offre->getPrixUn() != null){
            $choices[1] = 1;
        }
        if($offre->getPrixDouble() != null){
            $choices[2] = 2;
        }
        if($offre->getPrixTriple() != null){
            $choices[3] = 3;
        }
        if($offre->getPrixQuad() != null){
            $choices[4] = 4;
        }
        if($offre->getPrixQuint() != null){
            $choices[5] = 5;
        }        
        // dd(json_decode(json_encode($choices)));

        $commercials = $commercialApiService->getCommercialsJSON();
        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'idOffre' => $_route_params['idO'],
            'commercials' => str_replace(" ","$",$commercials),
            'choices' => json_decode(json_encode($choices)),
        ]);
    }

}
