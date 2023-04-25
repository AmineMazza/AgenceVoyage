<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Offre;
use App\Form\OffreType;
use App\Service\HotelApiService;
use App\Service\OffreApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/offre')]
class OffreController extends AbstractController
{
    #[Route('/', name: 'app_offre_index', methods: ['GET'])]
    public function index(OffreApiService $offreApiService): Response
    {
        return $this->render('offre/index.html.twig', [
            'offres' => $offreApiService->getOffres(),
        ]);
    }

    #[Route('/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OffreApiService $offreApiService): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $offreApiService->addOffre($offre);

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offre_show', methods: ['GET'])]
    public function show(array $_route_params, OffreApiService $offreApiService): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offreApiService->getOffre($_route_params['id']),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, OffreApiService $offreApiService, HotelApiService $hotelApiService): Response
    {
        foreach ($hotelApiService->getHotelsParams(["id_offre"=> $offre->getId()]) as $key => $value) {
            $hotel = new Hotel();
            $hotel->setId($value->id);
            $hotel->setLieu($value->lieu);
            $hotel->setEtoile($value->etoile);
            $hotel->setDistance($value->distance);
            $hotel->setNombreNuits($value->nombre_nuits);
            $hotel->setidOffre($offre);
            $offre->addHotel($hotel);
        }
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offreApiService->updateOffre($offre);

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request,array $_route_params, OffreApiService $offreApiService, HotelApiService $hotelApiService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$_route_params['id'], $request->request->get('_token'))) {
            $offreApiService->DeleteOffre($_route_params['id']);
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }
}
