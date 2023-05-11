<?php

namespace App\Controller;

use App\Entity\Voyageur;
use App\Form\VoyageurType;
use App\Service\ReservationApiService;
use App\Service\VoyageurApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/reservation/{idR}/voyageur')]
class VoyageurController extends AbstractController
{
    #[Route('/', name: 'app_voyageur_index', methods: ['GET'])]
    public function index(VoyageurApiService $VoyageurApiService, array $_route_params, ReservationApiService $reservationApiService): Response
    {
        if($this->isGranted('ROLE_ADMIN')){
            $reservation =  $reservationApiService->getReservation($_route_params['idR']);
            if ($reservation->getId() == null ) {
                return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
            }
            else{
                if ($reservation->getIdUser()->getId() != $this->getUser()->getId()) {
                    return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
                }
            }
        }
        return $this->render('voyageur/index.html.twig', [
            'voyageurs' => $VoyageurApiService->getVoyageurs($_route_params['idR']),
            'idR' => $_route_params['idR'],
        ]);
    }

    #[Route('/new', name: 'app_voyageur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VoyageurApiService $VoyageurApiService, array $_route_params, ReservationApiService $reservationApiService): Response
    {
        $voyageur = new Voyageur();
        if($this->isGranted('ROLE_ADMIN')){
            $reservation =  $reservationApiService->getReservation($_route_params['idR']);
            if ($reservation->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN")) {
                return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        $form = $this->createForm(VoyageurType::class, $voyageur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $VoyageurApiService->AddVoyageur($voyageur, $_route_params['idR']);

            return $this->redirectToRoute('app_voyageur_index', ['idR' => $_route_params['idR']], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voyageur/new.html.twig', [
            'voyageur' => $voyageur,
            'form' => $form,
            'idR' => $_route_params['idR'],
        ]);
    }

    #[Route('/{id}', name: 'app_voyageur_show', methods: ['GET'])]
    public function show(Voyageur $voyageur, array $_route_params): Response
    {
        if ($voyageur->getIdReservation()->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN") ) {
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('voyageur/show.html.twig', [
            'voyageur' => $voyageur,
            'idR' => $_route_params['idR'],
        ]);
    }

    #[Route('/{id}/edit', name: 'app_voyageur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voyageur $voyageur, VoyageurApiService $VoyageurApiService, array $_route_params): Response
    {
        if ($voyageur->getIdReservation()->getIdUser()->getId() != $this->getUser()->getId()  && !$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(VoyageurType::class, $voyageur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $VoyageurApiService->UpdateVoyageur($voyageur);

            return $this->redirectToRoute('app_voyageur_index', ['idR' => $_route_params['idR']], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voyageur/edit.html.twig', [
            'voyageur' => $voyageur,
            'form' => $form,
            'idR' => $_route_params['idR'],
        ]);
    }

    #[Route('/{id}', name: 'app_voyageur_delete', methods: ['POST'])]
    public function delete(Request $request, Voyageur $voyageur, VoyageurApiService $VoyageurApiService, array $_route_params): Response
    {
        if ($voyageur->getIdReservation()->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$voyageur->getId(), $request->request->get('_token'))) {
            $VoyageurApiService->DeleteVoyageur($voyageur->getId());
        }

        return $this->redirectToRoute('app_voyageur_index', ['idR' => $_route_params['idR']], Response::HTTP_SEE_OTHER);
    }
}
