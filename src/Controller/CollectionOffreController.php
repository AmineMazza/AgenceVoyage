<?php

namespace App\Controller;

use App\Entity\CollectionOffre;
use App\Form\CollectionOffreType;
use App\Repository\CollectionOffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/collection/offre')]
class CollectionOffreController extends AbstractController
{
    #[Route('/', name: 'app_collection_offre_index', methods: ['GET'])]
    public function index(CollectionOffreRepository $collectionOffreRepository): Response
    {
        return $this->render('collection_offre/index.html.twig', [
            'collection_offres' => $collectionOffreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_collection_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CollectionOffreRepository $collectionOffreRepository): Response
    {
        $collectionOffre = new CollectionOffre();
        $form = $this->createForm(CollectionOffreType::class, $collectionOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $collectionOffreRepository->save($collectionOffre, true);

            return $this->redirectToRoute('app_collection_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('collection_offre/new.html.twig', [
            'collection_offre' => $collectionOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_collection_offre_show', methods: ['GET'])]
    public function show(CollectionOffre $collectionOffre): Response
    {
        return $this->render('collection_offre/show.html.twig', [
            'collection_offre' => $collectionOffre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_collection_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CollectionOffre $collectionOffre, CollectionOffreRepository $collectionOffreRepository): Response
    {
        $form = $this->createForm(CollectionOffreType::class, $collectionOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $collectionOffreRepository->save($collectionOffre, true);

            return $this->redirectToRoute('app_collection_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('collection_offre/edit.html.twig', [
            'collection_offre' => $collectionOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_collection_offre_delete', methods: ['POST'])]
    public function delete(Request $request, CollectionOffre $collectionOffre, CollectionOffreRepository $collectionOffreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collectionOffre->getId(), $request->request->get('_token'))) {
            $collectionOffreRepository->remove($collectionOffre, true);
        }

        return $this->redirectToRoute('app_collection_offre_index', [], Response::HTTP_SEE_OTHER);
    }
}
