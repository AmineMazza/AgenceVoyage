<?php

namespace App\Controller;

use App\Entity\Avance;
use App\Form\AvanceType;
use App\Repository\AvanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/avance')]
class AvanceController extends AbstractController
{
    #[Route('/', name: 'app_avance_index', methods: ['GET'])]
    public function index(AvanceRepository $avanceRepository): Response
    {
        return $this->render('avance/index.html.twig', [
            'avances' => $avanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_avance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AvanceRepository $avanceRepository): Response
    {
        $avance = new Avance();
        $form = $this->createForm(AvanceType::class, $avance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avanceRepository->save($avance, true);

            return $this->redirectToRoute('app_avance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avance/new.html.twig', [
            'avance' => $avance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avance_show', methods: ['GET'])]
    public function show(Avance $avance): Response
    {
        return $this->render('avance/show.html.twig', [
            'avance' => $avance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avance $avance, AvanceRepository $avanceRepository): Response
    {
        $form = $this->createForm(AvanceType::class, $avance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avanceRepository->save($avance, true);

            return $this->redirectToRoute('app_avance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avance/edit.html.twig', [
            'avance' => $avance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avance_delete', methods: ['POST'])]
    public function delete(Request $request, Avance $avance, AvanceRepository $avanceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avance->getId(), $request->request->get('_token'))) {
            $avanceRepository->remove($avance, true);
        }

        return $this->redirectToRoute('app_avance_index', [], Response::HTTP_SEE_OTHER);
    }
}
