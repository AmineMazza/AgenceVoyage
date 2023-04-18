<?php

namespace App\Controller;

use App\Entity\Voyageur;
use App\Form\VoyageurType;
use App\Repository\VoyageurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/voyageur')]
class VoyageurController extends AbstractController
{
    #[Route('/', name: 'app_voyageur_index', methods: ['GET'])]
    public function index(VoyageurRepository $voyageurRepository): Response
    {
        return $this->render('voyageur/index.html.twig', [
            'voyageurs' => $voyageurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_voyageur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VoyageurRepository $voyageurRepository): Response
    {
        $voyageur = new Voyageur();
        $form = $this->createForm(VoyageurType::class, $voyageur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voyageurRepository->save($voyageur, true);

            return $this->redirectToRoute('app_voyageur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voyageur/new.html.twig', [
            'voyageur' => $voyageur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_voyageur_show', methods: ['GET'])]
    public function show(Voyageur $voyageur): Response
    {
        return $this->render('voyageur/show.html.twig', [
            'voyageur' => $voyageur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_voyageur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voyageur $voyageur, VoyageurRepository $voyageurRepository): Response
    {
        $form = $this->createForm(VoyageurType::class, $voyageur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voyageurRepository->save($voyageur, true);

            return $this->redirectToRoute('app_voyageur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voyageur/edit.html.twig', [
            'voyageur' => $voyageur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_voyageur_delete', methods: ['POST'])]
    public function delete(Request $request, Voyageur $voyageur, VoyageurRepository $voyageurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voyageur->getId(), $request->request->get('_token'))) {
            $voyageurRepository->remove($voyageur, true);
        }

        return $this->redirectToRoute('app_voyageur_index', [], Response::HTTP_SEE_OTHER);
    }
}
