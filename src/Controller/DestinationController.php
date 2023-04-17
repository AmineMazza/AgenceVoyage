<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Form\DestinationType;
use App\Repository\DestinationRepository;
use App\Service\DestinationApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/destination')]
class DestinationController extends AbstractController
{
    #[Route('/', name: 'app_destination_index', methods: ['GET'])]
    public function index(DestinationApiService $destinationApiService, UrlGeneratorInterface $urlGenerator): Response
    {
        try{
            return $this->render('destination/index.html.twig', [
                'destinations' => $destinationApiService->getDestinations(),
            ]);
        } catch(ClientException $e){
            if($e->getCode()===401)
                return new RedirectResponse($urlGenerator->generate('app_logout'));
        }
    }

    #[Route('/new', name: 'app_destination_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DestinationApiService $destinationApiService, UrlGeneratorInterface $urlGenerator): Response
    {

        try {
            $destination = new Destination();
            $form = $this->createForm(DestinationType::class, $destination);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $destinationApiService->AddDestination($destination);

                return $this->redirectToRoute('app_destination_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('destination/new.html.twig', [
                'destination' => $destination,
                'form' => $form,
            ]);
        } catch(ClientException $e){
            if($e->getCode()===401)
                return new RedirectResponse($urlGenerator->generate('app_logout'));
        }
    }

    #[Route('/{id}', name: 'app_destination_show', methods: ['GET'])]
    public function show(DestinationApiService $destinationApiService, UrlGeneratorInterface $urlGenerator, array $_route_params): Response
    {
        try {
            return $this->render('destination/show.html.twig', [
                'destination' => $destinationApiService->getDestination($_route_params['id']),
            ]);
        } catch(ClientException $e){
            if($e->getCode()===401)
                return new RedirectResponse($urlGenerator->generate('app_logout'));
        }
    }

    #[Route('/{id}/edit', name: 'app_destination_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DestinationApiService $destinationApiService, UrlGeneratorInterface $urlGenerator, array $_route_params): Response
    {

        try {
            $destination = $destinationApiService->getDestination($_route_params['id']);
            $form = $this->createForm(DestinationType::class, $destination);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $destinationApiService->UpdateDestination($destination);

                return $this->redirectToRoute('app_destination_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('destination/edit.html.twig', [
                'destination' => $destination,
                'form' => $form,
            ]);
        } catch(ClientException $e){
            if($e->getCode()===401)
                return new RedirectResponse($urlGenerator->generate('app_logout'));
        }
    }

    #[Route('/{id}', name: 'app_destination_delete', methods: ['POST'])]
    public function delete(Request $request, array $_route_params, DestinationApiService $destinationApiService, UrlGeneratorInterface $urlGenerator): Response
    {
        try {
            if ($this->isCsrfTokenValid('delete'.$_route_params['id'], $request->request->get('_token'))) {
                $destinationApiService->DeleteDestination($_route_params['id']);
            }

            return $this->redirectToRoute('app_destination_index', [], Response::HTTP_SEE_OTHER);
        } catch(ClientException $e){
            if($e->getCode()===401)
                return new RedirectResponse($urlGenerator->generate('app_logout'));
        }
    }
}
