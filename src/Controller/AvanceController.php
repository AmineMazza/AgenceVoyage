<?php

namespace App\Controller;

use App\Entity\Avance;
use App\Entity\Reservation;
use App\Form\AvanceType;
use App\Service\AvanceApiService;
use App\Service\PdfService;
use App\Service\ReservationApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/reservation/{idR}/avance')]
class AvanceController extends AbstractController
{
    #[Route('/', name: 'app_avance_index', methods: ['GET'])]
    public function index(AvanceApiService $avanceRepository,array $_route_params, ReservationApiService $reservationApiService): Response
    {
        $reservation =  $reservationApiService->getReservation($_route_params['idR']);
        if(!$this->isGranted('ROLE_ADMIN')){
            if ($reservation->getId() == null ) {
                return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
            }
            else{
                if ($reservation->getIdUser()->getId() != $this->getUser()->getId()) {
                    return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
                }
            }
        }
        $avances = $avanceRepository->getAvances($_route_params['idR']);
        $totalAvance = 0;
        foreach($avances as $avance){
            $totalAvance += $avance->getMontant();
        }
        return $this->render('avance/index.html.twig', [
            'avances' => $avances,
            'idR' => $_route_params['idR'],
            'totalAvance' => $totalAvance,
            'montantTotal' => $reservation->getMontantTotal(),
            'reste' => $reservation->getMontantTotal()-$totalAvance,
        ]);
    }

    #[Route('/new', name: 'app_avance_new', methods: ['GET', 'POST'])]
    public function new(Request $request,array $_route_params , AvanceApiService $avanceRepository, ReservationApiService $reservationApiService): Response
    {
        $avance = new Avance();
        $reservation =  $reservationApiService->getReservation($_route_params['idR']);
        if($this->isGranted('ROLE_ADMIN')){
            if ($reservation->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN")) {
                return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        
        $form = $this->createForm(AvanceType::class, $avance);
        $form->handleRequest($request);
        $avances = $avanceRepository->getAvances($_route_params['idR']);
        $totalAvance = 0;
        foreach($avances as $avan){
            $totalAvance += $avan->getMontant();
        }
        if($reservation->getMontantTotal() == $totalAvance){
            return $this->redirectToRoute('app_avance_index', ['idR' => $_route_params['idR']], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if(($reservation->getMontantTotal()-($totalAvance+$avance->getMontant()))>=0) $avanceRepository->AddAvance($avance,$_route_params['idR']);

            return $this->redirectToRoute('app_avance_index', ['idR' => $_route_params['idR']], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avance/new.html.twig', [
            'avance' => $avance,
            'form' => $form,
            'idR' => $_route_params['idR'],
        ]);
    }

    #[Route('/{id}', name: 'app_avance_show', methods: ['GET'])]
    public function show(Avance $avance, array $_route_params, ): Response
    {
        if ($avance->getIdReservation()->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN") ) {
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('avance/show.html.twig', [
            'avance' => $avance,
            'idR' => $_route_params['idR'],
        ]);
    }

    #[Route('/Recu/{id}', name: 'app_avance_recu', methods: ['GET'])]
    public function Recu(Avance $avance, PdfService $pdfService, Reservation $Reservation)
    {
        // if ($avance->getIdReservation()->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN") ) {
        //     return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        // }
        // $totalAvance = 0;
        // foreach($avance->getIdReservation()->getAvances() as $avance){
        //     $totalAvance += $avance->getMontant();
        // }

        // $html = $this->renderView('avance/recu.html.twig', [
        //     'totalAvance' => $totalAvance,
        //     'avance' => $avance,
        // ]);
        $montantTotalReservation = $Reservation->getMontantTotal();
        $totalAvance = 0;
    
        foreach ($Reservation->getAvances() as $avance) {
            $totalAvance += $avance->getMontant();
        }
    
        $montantRestant = $montantTotalReservation - $totalAvance;
    
        $html = $this->renderView('avance/recu.html.twig', [
            'montantTotalReservation' => $montantTotalReservation,
            'totalAvance' => $totalAvance,
            'montantRestant' => $montantRestant,
            'avance' => $avance,
        ]);
        $pdfService->showPdf($html);
    }

    #[Route('/{id}/edit', name: 'app_avance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avance $avance,array $_route_params, AvanceApiService $avanceRepository): Response
    {
        if ($avance->getIdReservation()->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(AvanceType::class, $avance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avanceRepository->updateAvance($avance, true);

            return $this->redirectToRoute('app_avance_index', ['idR' => $_route_params['idR']], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avance/edit.html.twig', [
            'avance' => $avance,
            'form' => $form,
            'idR' => $_route_params['idR'],
        ]);
    }

    #[Route('/{id}', name: 'app_avance_delete', methods: ['POST'])]
    public function delete(Request $request, Avance $avance, array $_route_params, AvanceApiService $avanceRepository): Response
    {
        if ($avance->getIdReservation()->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$_route_params['id'], $request->request->get('_token'))) {
            $avanceRepository->DeleteAvance($_route_params['id']);
        }

        return $this->redirectToRoute('app_avance_index', ['idR' => $_route_params['idR']], Response::HTTP_SEE_OTHER);
    }
}
