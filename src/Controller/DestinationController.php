<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Form\DestinationType;
use App\Service\CallApiService;
use App\Service\DestinationApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/destination')]
class DestinationController extends AbstractController
{
    #[Route('/', name: 'app_destination_index', methods: ['GET'])]
    public function index(DestinationApiService $destinationApiService, CallApiService $callApiService): Response
    {
        try{
            return $this->render('destination/index.html.twig', [
                'destinations' => $destinationApiService->getDestinations(),
            ]);
        } catch(ClientException $e){
            if($e->getCode()===401){
                $callApiService->getJWTRefreshToken();
                return $this->redirectToRoute('app_destination_index', [], Response::HTTP_SEE_OTHER);
            }
        }
    }

    #[Route('/new', name: 'app_destination_new', methods: ['POST'])]
    public function new(Request $request, DestinationApiService $destinationApiService): Response
    {

        $destination = new Destination();
        $destination->setPays($request->request->get('destination_pays'));

        $destinationApiService->AddDestination($destination);
        return $this->redirectToRoute('app_destination_index', [], Response::HTTP_SEE_OTHER);

    }

    #[Route('/{id}/edit', name: 'app_destination_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DestinationApiService $destinationApiService, CallApiService $callApiService, array $_route_params): Response
    {
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
    }

    #[Route('/{id}', name: 'app_destination_delete', methods: ['POST'])]
    public function delete(Request $request, array $_route_params, DestinationApiService $destinationApiService, CallApiService $callApiService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$_route_params['id'], $request->request->get('_token'))) {
            $destinationApiService->DeleteDestination($_route_params['id']);
        }

        return $this->redirectToRoute('app_destination_index', [], Response::HTTP_SEE_OTHER);
    }
}
