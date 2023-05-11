<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Service\ReservationApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationApiService $ReservationApiService, Security $security): Response
    {
        if ($this->isGranted("ROLE_ADMIN")) $data = $ReservationApiService->getReservations();
        else $data = $ReservationApiService->getReservations(['id_user' => $this->getUser()->getId()]);
        return $this->render('reservation/index.html.twig', [
            'reservations' => $data,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        if ($reservation->getIdUser()->getId() != $this->getUser()->getId()  && !$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationApiService $ReservationApiService): Response
    {
        // dd($reservation);
        if ($reservation->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
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
        if ($reservation->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $ReservationApiService->DeleteReservation($reservation->getId());
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
