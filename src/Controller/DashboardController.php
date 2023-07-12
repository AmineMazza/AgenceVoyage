<?php

namespace App\Controller;
use Exception;
use App\Entity\Commercial;
use App\Service\HotelApiService;
use App\Form\OffreType;
use App\Service\DestinationApiService;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Offre;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\AgentRepository;
use App\Repository\CommercialRepository;
use App\Repository\MessageRepository;
use App\Repository\OffreRepository;
use App\Repository\ReservationRepository;
use App\Service\CommercialApiService;
use App\Service\OffreApiService;
use App\Service\ReservationApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(OffreRepository $offreRepository,AgentRepository $agentRepository,CommercialRepository $commercialRepository,MessageRepository $messageRepository,ReservationRepository $reservationRepository): Response
    {
        if($this->isGranted("ROLE_ADMIN")){
            return $this->render('dashboard/index.html.twig', [
                'controller_name' => 'DashboardController',
                'offres'=> $offreRepository->countOffre(),
                'agent'=>$agentRepository->countAgent(),
                'messages'=>$messageRepository->countMessage(),
                'commercial'=>$commercialRepository->countCommercial(),
                'reservation'=>$reservationRepository->countReservation(),
            ]);
        }
        else if($this->isGranted("ROLE_AGENT")){
            return $this->render('dashboard/index.html.twig', [
                'agent'=>null,
                'controller_name' => 'DashboardController',
                'offres'=> $offreRepository->countOffreByUser($this->getUser()->getId()),
                'messages'=>$messageRepository->CountMessagesByUser($this->getUser()->getId())[0][1],
                'commercial'=>$commercialRepository->countCommercialByUser($this->getUser()->getAgent()->getId()),
                'reservation'=>$reservationRepository->countReservationByUser($this->getUser()->getId()),
            ]);
        }
        
    }

    #[Route('/myOffres', name: 'app_myoffres')]
    public function myoffres(OffreApiService $offreApiService): Response
    {
        if($this->isGranted("ROLE_ADMIN")){
            $offres = $offreApiService->getOffres();
        }
        else $offres = $offreApiService->getOffresParams(['id_user'=>$this->getUser()->getId()]);
        return $this->render('dashboard/myoffres.html.twig', [
            'offres' => $offres,
        ]);
    }
    #[Route('/offre/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OffreApiService $offreApiService, SluggerInterface $slugger): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();
            if ($file){
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                
                
                $offre->setImage('/assets/images/offres/'.$newFilename);
                
            }
            $status = $offreApiService->addOffre($offre);
            if($status){
                if($file){
                    try {
                        $file->move(
                            $this->getParameter('offres_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        throw new Exception($e);
                    }
                }
            }

            return $this->redirectToRoute('app_offre_index', ['value'=>'all'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }


    #[Route('/offre/{id}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, OffreApiService $offreApiService, HotelApiService $hotelApiService): Response
    {
        // dd($offre->getHotels());
        if($this->getUser() && !$this->isGranted('ROLE_USER')){
            if ($offre->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN")) {
                return $this->redirectToRoute('app_offre_index', ['value' => 'all'], Response::HTTP_SEE_OTHER);
            }
        }
        else{
            return $this->redirectToRoute('app_offre_show', ['id' => $offre->getId()], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offreApiService->updateOffre($offre);

            return $this->redirectToRoute('app_offre_index', ['value'=>'all'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }


    #[Route('/offre/{id}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request,array $_route_params, OffreApiService $offreApiService, HotelApiService $hotelApiService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$_route_params['id'], $request->request->get('_token'))) {
            $offreApiService->DeleteOffre($_route_params['id']);
        }

        return $this->redirectToRoute('app_offre_index', ['value'=>'all'], Response::HTTP_SEE_OTHER);
    }


    #[Route('/myOffres/{idO}/reserver', name: 'app_myoffres_reserver', methods: ['GET', 'POST'])]
    public function reserver(Request $request ,array $_route_params , ReservationApiService $ReservationApiService, CommercialApiService $commercialApiService, OffreApiService $offreApiService): Response
    {
        $offre = $offreApiService->getOffre($_route_params['idO']);
        // dd($offre);
        if(!$this->isGranted("ROLE_ADMIN")){
            if ($offre->getIdUser()->getId() != $this->getUser()->getId()) {
                return $this->redirectToRoute('app_myoffres', [], Response::HTTP_SEE_OTHER);
            }
        }
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $extraFields = $form->getExtraData();
            $mantant = 0;
            
            // dd($extraFields);
            $i = 0;
            if($extraFields['voyageur'] != []){
                foreach ($extraFields['voyageur'] as $key => $value){
                    if($value['prix']!=""){
                        if($value['prix'] == 'prixUn'){
                            $reservation->getVoyageurs()[$key]->setChambre("chambre seul");
                            $reservation->getVoyageurs()[$key]->setMontant($offre->getPrixUn());
                            $mantant += $offre->getPrixUn();
                        }
                        elseif($value['prix'] == 'prixDouble'){
                            $reservation->getVoyageurs()[$key]->setChambre("chambre double");
                            $reservation->getVoyageurs()[$key]->setMontant($offre->getPrixDouble());
                            $mantant += $offre->getPrixDouble();
                        }
                        elseif($value['prix'] == 'prixTriple'){
                            $reservation->getVoyageurs()[$key]->setChambre("chambre triple");
                            $reservation->getVoyageurs()[$key]->setMontant($offre->getPrixTriple());
                            $mantant += $offre->getPrixTriple();
                        }
                        elseif($value['prix'] == 'prixQuad'){
                            $reservation->getVoyageurs()[$key]->setChambre("chambre double");
                            $reservation->getVoyageurs()[$key]->setMontant($offre->getPrixQuad());
                            $mantant += $offre->getPrixQuad();
                        }
                        elseif($value['prix'] == 'prixQuint'){
                            $reservation->getVoyageurs()[$key]->setChambre("chambre quint");
                            $reservation->getVoyageurs()[$key]->setMontant($offre->getPrixQuint());
                            $mantant += $offre->getPrixQuint();
                        }
                        $i++;
                    }
                    if($value['pension'] != ""){
                        if($value['pension'] == 'demiPension'){
                            $reservation->getVoyageurs()[$key]->setPension("demi pension");
                            $reservation->getVoyageurs()[$key]->setMontant($reservation->getVoyageurs()[$key]->getMontant()+$offre->getPrixDemiPension());
                            $mantant += $offre->getPrixDemiPension  ();
                        }
                        elseif($value['pension'] == 'pensionComplete'){
                            $reservation->getVoyageurs()[$key]->setPension("pension complete");
                            $reservation->getVoyageurs()[$key]->setMontant($reservation->getVoyageurs()[$key]->getMontant()+$offre->getPrixCompletePension());
                            $mantant += $offre->getPrixCompletePension();
                        }
                        
                    }
                    
                }
                if($i!=$reservation->getNumVoyageurs()){
                    $mantant += ($offre->getPrixUn())*($reservation->getNumVoyageurs()-$i);
                }
            }
            else{
                $mantant = ($offre->getPrixUn())*($reservation->getNumVoyageurs());
            }
            $reservation->setMontantTotal($mantant);
            if(array_key_exists('isCommercial',$extraFields)){
                if($extraFields['isCommercial'] == 'on'){
                    $idR = $ReservationApiService->AddReservation($reservation, $_route_params['idO'],$extraFields['id_commercial']);
                }
            }
            else {
                $idR = $ReservationApiService->AddReservation($reservation, $_route_params['idO']);
            }
            // dd($reservation);
            

            return $this->redirectToRoute('app_voyageur_index', ['idR' => $idR], Response::HTTP_SEE_OTHER);
        }   
        // dd(json_decode(json_encode($choices)));

        $commercials = $commercialApiService->getCommercialsJSON();
        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'idOffre' => $_route_params['idO'],
            'commercials' => str_replace(" ","$",$commercials),
        ]);
    }

}
