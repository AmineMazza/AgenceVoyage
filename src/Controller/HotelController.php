<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Form\HotelType;
use App\Service\HotelApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/offre/{idH}/hotel')]
class HotelController extends AbstractController
{
    #[Route('/', name: 'app_hotel_index', methods: ['GET'])]
    public function index(HotelApiService $hotelApiService,array $_route_params): Response
    {
        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotelApiService->getHotelsParams(['id_offre'=>$_route_params['idH']]),
            'idH' => $_route_params['idH'],
        ]);
    }

    #[Route('/new', name: 'app_hotel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HotelApiService $hotelApiService,array $_route_params): Response
    {
        $hotel = new Hotel();
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hotelApiService->AddHotel($hotel,$_route_params['idH']);

            return $this->redirectToRoute('app_hotel_index', ['idH' => $_route_params['idH']], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hotel/new.html.twig', [
            'hotel' => $hotel,
            'form' => $form,
            'idH' => $_route_params['idH'],
        ]);
    }

    #[Route('/{id}', name: 'app_hotel_show', methods: ['GET'])]
    public function show(Hotel $hotel,array $_route_params): Response
    {
        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel,
            'idH' => $_route_params['idH'],
        ]);
    }

    #[Route('/{id}/edit', name: 'app_hotel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hotel $hotel, HotelApiService $hotelApiService, array $_route_params): Response
    {
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hotelApiService->UpdateHotel($hotel);

            return $this->redirectToRoute('app_hotel_index', ['idH' => $_route_params['idH']], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hotel/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form,
            'idH' => $_route_params['idH'],
        ]);
    }

    #[Route('/{id}', name: 'app_hotel_delete', methods: ['POST'])]
    public function delete(Request $request, Hotel $hotel, HotelApiService $hotelApiService, array $_route_params): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hotel->getId(), $request->request->get('_token'))) {
            $hotelApiService->DeleteHotel($hotel->getId());
        }

        return $this->redirectToRoute('app_hotel_index', ['idH' => $_route_params['idH']], Response::HTTP_SEE_OTHER);
    }
}
