<?php

namespace App\Controller;

use App\Entity\Commercial;
use App\Form\CommercialType;
use App\Repository\CommercialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commercial')]
class CommercialController extends AbstractController
{
    #[Route('/', name: 'app_commercial_index', methods: ['GET'])]
    public function index(CommercialRepository $commercialRepository): Response
    {
        return $this->render('commercial/index.html.twig', [
            'commercials' => $commercialRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commercial_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommercialRepository $commercialRepository): Response
    {
        $commercial = new Commercial();
        $form = $this->createForm(CommercialType::class, $commercial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commercialRepository->save($commercial, true);

            return $this->redirectToRoute('app_commercial_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commercial/new.html.twig', [
            'commercial' => $commercial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commercial_show', methods: ['GET'])]
    public function show(Commercial $commercial): Response
    {
        return $this->render('commercial/show.html.twig', [
            'commercial' => $commercial,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commercial_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commercial $commercial, CommercialRepository $commercialRepository): Response
    {
        $form = $this->createForm(CommercialType::class, $commercial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commercialRepository->save($commercial, true);

            return $this->redirectToRoute('app_commercial_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commercial/edit.html.twig', [
            'commercial' => $commercial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commercial_delete', methods: ['POST'])]
    public function delete(Request $request, Commercial $commercial, CommercialRepository $commercialRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commercial->getId(), $request->request->get('_token'))) {
            $commercialRepository->remove($commercial, true);
        }

        return $this->redirectToRoute('app_commercial_index', [], Response::HTTP_SEE_OTHER);
    }
}
