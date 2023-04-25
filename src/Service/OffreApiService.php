<?php

namespace App\Service;

use App\Entity\Hotel;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Offre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OffreApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, private HotelApiService $hotelApiService, TokenStorageInterface $tokenStorage, private DestinationApiService $destinationApiService){
        $this->tokenStorage = $tokenStorage;
    }

    public function getOffres() : array
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/offres',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        return $response->toArray();
    }

    public function getOffre($id) : Offre
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/offres/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        $offre = new Offre();
        $data = json_decode($response->getContent());
        $offre->setId($data->id);
        $offre->setTitre($data->titre);
        $offre->setImage((!empty($data->image) ? $data->image : null));
        $offre->setDateDepart(\DateTime::createFromFormat('Y-m-d\TH:i:sP',$data->date_depart));
        $offre->setDateRetour(\DateTime::createFromFormat('Y-m-d\TH:i:sP',$data->date_retour));
        $offre->setBallerRetour($data->baller_retour);
        $offre->setBhebergement($data->bhebergement);
        $offre->setBvisa($data->bvisa);
        $offre->setBpetitDejeuner($data->bpetit_dejeuner);
        $offre->setBdemiPension($data->bdemi_pension);
        $offre->setBpensionComplete($data->bpension_complete);
        $offre->setBvisiteMedine($data->bvisite_medine);
        $offre->setPrixChambre((!empty($data->prix_chambre) ? $data->prix_chambre : null));
        $offre->setPrixChambreDouble((!empty($data->prix_chambre_double) ? $data->prix_chambre_double : null));
        $offre->setPrixChambreTriple((!empty($data->prix_chambre_triple) ? $data->prix_chambre_triple : null));
        $offre->setPrixChambreQuad((!empty($data->prix_chambre_quad) ? $data->prix_chambre_quad : null));
        $offre->setPrixChambreQuint((!empty($data->prix_chambre_quint) ? $data->prix_chambre_quint : null));
        $offre->setBcoupCoeur($data->bcoup_coeur);
        $offre->setBpubier($data->bpubier);
        $offre->setDatePublication((!empty($offre->getDatePublication()) ? $offre->getDatePublication()->format('Y-m-d') : null));
        $offre->setDateFinPublication((!empty($offre->getDateFinPublication()) ? $offre->getDateFinPublication()->format('Y-m-d') : null));
        $offre->setBpassport($data->bpassport);
        $offre->setBphotos($data->bphotos);
        $offre->setBpassVacinial($data->bpass_vacinial);
        $offre->setPrix($data->prix);
        $offre->setDetailVoyage((!empty($data->detail_voyage) ? $data->detail_voyage : null));
        $offre->setDetailVols((!empty($data->detail_vols) ? $data->detail_vols : null));
        $arr_dest = explode('/',$data->idDestination);
        $destination = $this->destinationApiService->getDestination($arr_dest[count($arr_dest)-1]);
        $offre->setIdDestination($destination);
        $searchParams = [
            'id_offre' => $data->id,
        ];
        $hotels = $this->hotelApiService->getHotelsParams($searchParams);
        foreach ($hotels as $key => $value) {
            $hotel = new Hotel();
            $hotel->setId($value->id);
            $hotel->setLieu($value->lieu);
            $hotel->setEtoile($value->etoile);
            $hotel->setDistance($value->distance);
            $hotel->setNombreNuits($value->nombre_nuits);
            $hotel->setidOffre($offre);
            $offre->addHotel($hotel);
        }
        return $offre;
    }

    public function addOffre($offre) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $json = [ 
            'idUser' => '/api/users/'.$this->getUser()->getId(),
            'idDestination' =>  '/api/destinations/'.$offre->getIdDestination()->getId(),
            'titre' => $offre->getTitre(),
            'dateDepart' => $offre->getDateDepart()->format('Y-m-d\TH:i:sP'),
            'dateRetour' => $offre->getDateRetour()->format('Y-m-d\TH:i:sP'),
            'ballerRetour' => $offre->isBallerrEtour(),
            'bhebergement' => $offre->isBhebergement(),
            'bvisa' => $offre->isBvisa(),
            'bpetitDejeuner' => $offre->isBpetitDejeuner(),
            'bdemiPension' => $offre->isBdemiPension(),
            'bpensionComplete' => $offre->isBpensionComplete(),
            'bvisiteMedine' => $offre->isBvisiteMedine(),
            'prixChambre' => $offre->getPrixChambre(),
            'prixChambreDouble' => $offre->getPrixChambreDouble(),
            'prixChambreTriple' => $offre->getPrixChambreTriple(),
            'prixChambreQuad' => $offre->getPrixChambreQuad(),
            'prixChambreQuint' => $offre->getPrixChambreQuint(),
            'bcoupCoeur' => $offre->isBcoupCoeur(),
            'bpubier' => $offre->isBpubier(),
            'datePublication' => (!empty($offre->getDatePublication()) ? $offre->getDatePublication()->format('Y-m-d') : null),
            'dateFinPublication' => (!empty($offre->getDateFinPublication()) ? $offre->getDateFinPublication()->format('Y-m-d') : null),
            'bpassport' => $offre->isBpassport(),
            'bphotos' => $offre->isBphotos(),
            'bpassVacinial' => $offre->isBpassVacinial(),
            'prix' => $offre->getPrix(),
            'detailVoyage' => $offre->getDetailVoyage(),
            'detailVols' => $offre->getDetailVols(),
        ];

        $response = $this->client->request('POST', 'http://127.0.0.1/api/offres', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => $json,
        ]);
        if ($response->getStatusCode() === 201) {
            $foreignKeyResponseData = json_decode($response->getContent(), true);
            $foreignKeyId = $foreignKeyResponseData['id'];
            foreach ($offre->getHotels() as $key => $value) {
                $this->hotelApiService->AddHotel($value,$foreignKeyId);
            }
            return true;
        }
        return false;
    }

    public function updateOffre($offre) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $json = [ 
            'idUser' => '/api/users/'.$this->getUser()->getId(),
            'idDestination' =>  '/api/destinations/'.$offre->getIdDestination()->getId(),
            'titre' => $offre->getTitre(),
            'dateDepart' => $offre->getDateDepart()->format('Y-m-d\TH:i:sP'),
            'dateRetour' => $offre->getDateRetour()->format('Y-m-d\TH:i:sP'),
            'ballerRetour' => $offre->isBallerrEtour(),
            'bhebergement' => $offre->isBhebergement(),
            'bvisa' => $offre->isBvisa(),
            'bpetitDejeuner' => $offre->isBpetitDejeuner(),
            'bdemiPension' => $offre->isBdemiPension(),
            'bpensionComplete' => $offre->isBpensionComplete(),
            'bvisiteMedine' => $offre->isBvisiteMedine(),
            'prixChambre' => $offre->getPrixChambre(),
            'prixChambreDouble' => $offre->getPrixChambreDouble(),
            'prixChambreTriple' => $offre->getPrixChambreTriple(),
            'prixChambreQuad' => $offre->getPrixChambreQuad(),
            'prixChambreQuint' => $offre->getPrixChambreQuint(),
            'bcoupCoeur' => $offre->isBcoupCoeur(),
            'bpubier' => $offre->isBpubier(),
            'datePublication' => (!empty($offre->getDatePublication()) ? $offre->getDatePublication()->format('Y-m-d') : null),
            'dateFinPublication' => (!empty($offre->getDateFinPublication()) ? $offre->getDateFinPublication()->format('Y-m-d') : null),
            'bpassport' => $offre->isBpassport(),
            'bphotos' => $offre->isBphotos(),
            'bpassVacinial' => $offre->isBpassVacinial(),
            'prix' => $offre->getPrix(),
            'detailVoyage' => $offre->getDetailVoyage(),
            'detailVols' => $offre->getDetailVols(),
        ];
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/offres/'.$offre->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => $json,
        ]);
        if ($response->getStatusCode() === 201) {
            foreach ($offre->getHotels() as $key => $value) {
                // dd($value);
                $this->hotelApiService->UpdateHotel($value);
            }
            return true;
        }
        return false;
    }

    public function DeleteOffre($id) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('DELETE', 'http://127.0.0.1/api/offres/'. $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 201) {
            return true;
        }
        return false;
    }
}