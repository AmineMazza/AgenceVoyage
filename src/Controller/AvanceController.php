<?php

namespace App\Controller;

use App\Entity\Avance;
use App\Form\AvanceType;
use App\Repository\AvanceRepository;
use App\Service\AvanceApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation/{idR}/avance')]
class AvanceController extends AbstractController
{
    #[Route('/', name: 'app_avance_index', methods: ['GET'])]
    public function index(AvanceApiService $avanceRepository,array $_route_params): Response
    {
        return $this->render('avance/index.html.twig', [
            'avances' => $avanceRepository->getAvances($_route_params['idR']),
            'idR' => $_route_params['idR'],
        ]);
    }

    #[Route('/new', name: 'app_avance_new', methods: ['GET', 'POST'])]
    public function new(Request $request,array $_route_params , AvanceApiService $avanceRepository): Response
    {
        $avance = new Avance();
        $form = $this->createForm(AvanceType::class, $avance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avanceRepository->AddAvance($avance,$_route_params['idR']);

            return $this->redirectToRoute('app_avance_index', ['idR' => $_route_params['idR']], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avance/new.html.twig', [
            'avance' => $avance,
            'form' => $form,
            'idR' => $_route_params['idR'],
        ]);
    }

    #[Route('/{id}', name: 'app_avance_show', methods: ['GET'])]
    public function show(Avance $avance, array $_route_params): Response
    {
        return $this->render('avance/show.html.twig', [
            'avance' => $avance,
            'idR' => $_route_params['idR'],
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avance $avance,array $_route_params, AvanceApiService $avanceRepository): Response
    {
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
    public function delete(Request $request,array $_route_params , AvanceApiService $avanceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$_route_params['id'], $request->request->get('_token'))) {
            $avanceRepository->DeleteAvance($_route_params['id']);
        }

        return $this->redirectToRoute('app_avance_index', ['idR' => $_route_params['idR']], Response::HTTP_SEE_OTHER);
    }
}
