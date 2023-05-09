<?php

namespace App\Controller;

use App\Entity\Commercial;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Service\CommercialApiService;
use App\Service\ReservationApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationApiService $ReservationApiService): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $ReservationApiService->getReservations(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationApiService $ReservationApiService, CommercialApiService $commercialApiService): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $extraFields = $form->getExtraData();
            // dd($extraFields);
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
            // dd($reservation);
            $ReservationApiService->AddReservation($reservation);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        $commercials = $commercialApiService->getCommercialsJSON();
        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'commercials' => str_replace(" ","$",$commercials),
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationApiService $ReservationApiService): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ReservationApiService->UpdateReservation($reservation);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationApiService $ReservationApiService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $ReservationApiService->DeleteReservation($reservation->getId());
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
