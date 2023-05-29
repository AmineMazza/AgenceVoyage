<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Voyageur;
use App\Form\VoyageurType;
use App\Service\ReservationApiService;
use App\Service\VoyageurApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/reservation/{idR}/voyageur')]
class VoyageurController extends AbstractController
{

    private $requestStack;
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/', name: 'app_voyageur_index', methods: ['GET'])]
    public function index(VoyageurApiService $VoyageurApiService, array $_route_params, ReservationApiService $reservationApiService): Response
    {
        $reservation =  $reservationApiService->getReservation($_route_params['idR']);
        if(!$this->isGranted('ROLE_ADMIN')){
            if ($reservation->getIdUser()->getId() != $this->getUser()->getId()) {
                return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        $voyageurs = $VoyageurApiService->getVoyageurs($_route_params['idR']);
        $request = $this->requestStack->getMainRequest();
        if ($request->headers->has('referer')) {
            $previousRequestData =json_decode($request->cookies->get('sf_redirect'));
            if($previousRequestData != null){
                if($previousRequestData->method === "POST"){
                    $montantTotal = ($reservation->getNumVoyageurs()-count($voyageurs))*$reservation->getIdOffre()->getPrixUn();
                    foreach($voyageurs as $voyageur){
                        $montantTotal += $voyageur->getMontant();
                    }
                }
                $reservationApiService->UpdateReservationMontant($_route_params['idR'], $montantTotal);
            }
        }
        return $this->render('voyageur/index.html.twig', [
            'voyageurs' => $voyageurs,
            'idR' => $_route_params['idR'],
            'numVoyaveurs' => $reservation->getNumVoyageurs(),
            'numVoyaveursExists' => count($voyageurs),
        ]);
    }

    #[Route('/new', name: 'app_voyageur_new', methods: ['GET', 'POST'])]
    public function new(Request $request,Reservation $reservation = null,  VoyageurApiService $VoyageurApiService, array $_route_params, ReservationApiService $reservationApiService): Response
    {
        $voyageur = new Voyageur();
        $reservation =  $reservationApiService->getReservation($_route_params['idR']);
        $voyageurs = $VoyageurApiService->getVoyageurs($_route_params['idR']);
        if($reservation->getNumVoyageurs() == count($voyageurs)){
            return $this->redirectToRoute('app_voyageur_index', ['idR' => $_route_params['idR']], Response::HTTP_SEE_OTHER);
        }
        if($this->isGranted('ROLE_ADMIN')){
            if ($reservation->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN")) {
                return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        $form = $this->createForm(VoyageurType::class, $voyageur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $extarData = $form->getExtraData();
            $montant = 0;
            if($extarData != []){
                if($extarData['chambre'] == 'prixUn'){
                    $voyageur->setChambre("chambre seul");
                    $montant = $reservation->getIdOffre()->getPrixUn();
                }
                elseif($extarData['chambre'] == 'prixDouble'){
                    $voyageur->setChambre("chambre double");
                    $montant = $reservation->getIdOffre()->getPrixDouble();
                }
                elseif($extarData['chambre'] == 'prixTriple'){
                    $voyageur->setChambre("chambre triple");
                    $montant = $reservation->getIdOffre()->getPrixTriple();
                }
                elseif($extarData['chambre'] == 'prixQuad'){
                    $voyageur->setChambre("chambre quad");
                    $montant = $reservation->getIdOffre()->getPrixQuad();
                }
                elseif($extarData['chambre'] == 'prixQuint'){
                    $voyageur->setChambre("chambre quint");
                    $montant = $reservation->getIdOffre()->getPrixQuint();
                }

                if($extarData['pension'] != ''){
                    if($extarData['pension'] == 'demiPension'){
                        $voyageur->setPension("demi pension");
                        $montant += $reservation->getIdOffre()->getPrixDemiPension();
                    }
                    elseif($extarData['pension'] == 'pensionComplete'){
                        $voyageur->setPension("pension complete");
                        $montant += $reservation->getIdOffre()->getPrixCompletePension();
                    }
                }

            }
            $voyageur->setMontant($montant);
            $VoyageurApiService->AddVoyageur($voyageur, $_route_params['idR']);

            return $this->redirectToRoute('app_voyageur_index', ['idR' => $_route_params['idR']], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voyageur/new.html.twig', [
            'voyageur' => $voyageur,
            'form' => $form,
            'idR' => $_route_params['idR'],
            'offre' => $reservation->getIdOffre(),
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
            $extarData = $form->getExtraData();
            $montant = 0;
            if($extarData != []){
                if($extarData['chambre'] == 'prixUn'){
                    $voyageur->setChambre("chambre seul");
                    $montant = $voyageur->getIdReservation()->getIdOffre()->getPrixUn();
                }
                elseif($extarData['chambre'] == 'prixDouble'){
                    $voyageur->setChambre("chambre double");
                    $montant = $voyageur->getIdReservation()->getIdOffre()->getPrixDouble();
                }
                elseif($extarData['chambre'] == 'prixTriple'){
                    $voyageur->setChambre("chambre triple");
                    $montant = $voyageur->getIdReservation()->getIdOffre()->getPrixTriple();
                }
                elseif($extarData['chambre'] == 'prixQuad'){
                    $voyageur->setChambre("chambre quad");
                    $montant = $voyageur->getIdReservation()->getIdOffre()->getPrixQuad();
                }
                elseif($extarData['chambre'] == 'prixQuint'){
                    $voyageur->setChambre("chambre quint");
                    $montant = $voyageur->getIdReservation()->getIdOffre()->getPrixQuint();
                }

                if($extarData['pension'] != ''){
                    if($extarData['pension'] == 'demiPension'){
                        $voyageur->setPension("demi pension");
                        $montant += $voyageur->getIdReservation()->getIdOffre()->getPrixDemiPension();
                    }
                    elseif($extarData['pension'] == 'pensionComplete'){
                        $voyageur->setPension("pension complete");
                        $montant += $voyageur->getIdReservation()->getIdOffre()->getPrixCompletePension();
                    }
                }

            }
            $voyageur->setMontant($montant);
            $VoyageurApiService->UpdateVoyageur($voyageur);

            return $this->redirectToRoute('app_voyageur_index', ['idR' => $_route_params['idR']], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voyageur/edit.html.twig', [
            'voyageur' => $voyageur,
            'form' => $form,
            'idR' => $_route_params['idR'],
            'offre' => $voyageur->getIdReservation()->getIdOffre(),
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
