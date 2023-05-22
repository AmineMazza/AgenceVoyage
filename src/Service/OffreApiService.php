<?php

namespace App\Service;

use App\Entity\Hotel;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Offre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OffreApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, private HotelApiService $hotelApiService, TokenStorageInterface $tokenStorage, private DestinationApiService $destinationApiService, private CallApiService $callApiService, private UserApiService $userApiService){
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
        $offres = $response->toArray();
        foreach ($offres as $key => $data) {
            //dd($data);
            $arr_dest = explode('/',$data['id_destination']);
            $destination = $this->destinationApiService->getDestination($arr_dest[count($arr_dest)-1]);
            $offres[$key]['id_destination']=$destination;
            $offres[$key]['idDestination']=$destination;
        }
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getOffres();
        }
        return $offres;
    }

    public function getOffresParams($params) : array
    {
        $response = $this->client->request('GET', 'http://127.0.0.1/api/offres',[
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query' => $params,
        ]);
        $offres = $response->toArray();
        foreach ($offres as $key => $data) {
            //dd($data);
            $arr_dest = explode('/',$data['id_destination']);
            $destination = $this->destinationApiService->getDestination($arr_dest[count($arr_dest)-1]);
            $offres[$key]['id_destination']=$destination;
            $offres[$key]['idDestination']=$destination;
        }
        return $offres;
    }

    public function getOffre($id) : Offre
    {
        $response = $this->client->request('GET', 'http://127.0.0.1/api/offres/'.$id, [
            'headers' => [
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
        $offre->setPrixDemiPension($data->bdemi_pension ? $data->prix_demi_pension : null);
        $offre->setDetailDemiPension($data->bdemi_pension ? (!empty($data->detail_demi_pension) ? $data->detail_demi_pension : null) : null);
        $offre->setBpensionComplete($data->bpension_complete);
        $offre->setPrixCompletePension($data->bpension_complete ? $data->prix_complete_pension : null);
        $offre->setDetailCompletePension($data->bpension_complete ? (!empty($data->detail_complete_pension) ? $data->detail_complete_pension : null) : null);
        $offre->setBvisiteMedine($data->bvisite_medine);
        $offre->setPrixUn((!empty($data->prix_un) ? $data->prix_un : null));
        $offre->setPrixDouble((!empty($data->prix_double) ? $data->prix_double : null));
        $offre->setPrixTriple((!empty($data->prix_triple) ? $data->prix_triple : null));
        $offre->setPrixQuad((!empty($data->prix_quad) ? $data->prix_quad : null));
        $offre->setPrixQuint((!empty($data->prix_quint) ? $data->prix_quint : null));
        $offre->setBcoupCoeur($data->bcoup_coeur);
        $offre->setBpubier($data->bpubier);
        $offre->setDatePublication((!empty($offre->getDatePublication()) ? $offre->getDatePublication()->format('Y-m-d') : null));
        $offre->setDateFinPublication((!empty($offre->getDateFinPublication()) ? $offre->getDateFinPublication()->format('Y-m-d') : null));
        $offre->setBpassport($data->bpassport);
        $offre->setBphotos($data->bphotos);
        $offre->setBpassVacinial($data->bpass_vacinial);
        $offre->setDetailVoyage((!empty($data->detail_voyage) ? $data->detail_voyage : null));
        $offre->setDetailVols((!empty($data->detail_vols) ? $data->detail_vols : null));
        $arr_dest = explode('/',$data->idDestination);
        $destination = $this->destinationApiService->getDestination($arr_dest[count($arr_dest)-1]);
        $offre->setIdDestination($destination);
        $arr_dest = explode('/',$data->id_user);
        $user = $this->userApiService->getOneUser($arr_dest[count($arr_dest)-1]);
        $offre->setIdUser($user);
        $hotels = $this->hotelApiService->getHotelsParams(['id_offre' => $data->id]);
        foreach ($hotels as $key => $value) {
            $hotel = new Hotel();
            $hotel->setId($value->id);
            $hotel->setLieu($value->lieu);
            $hotel->setEtoile($value->etoile);
            $hotel->setDistance($value->distance);
            $hotel->setName($value->name);
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
            'idUser' => ($offre->getIdUser()!=null ? '/api/users/'.$offre->getIdUser()->geId() : '/api/users/'.$this->getUser()->getId()),
            'idDestination' =>  '/api/destinations/'.$offre->getIdDestination()->getId(),
            'titre' => $offre->getTitre(),
            'dateDepart' => $offre->getDateDepart()->format('Y-m-d\TH:i:sP'),
            'dateRetour' => $offre->getDateRetour()->format('Y-m-d\TH:i:sP'),
            'ballerRetour' => $offre->isBallerrEtour(),
            'bhebergement' => $offre->isBhebergement(),
            'bvisa' => $offre->isBvisa(),
            'bpetitDejeuner' => $offre->isBpetitDejeuner(),
            'bdemiPension' => $offre->isBdemiPension(),
            'prixDemiPension' => $offre->isBdemiPension() ? $offre->getPrixDemiPension() : null,
            'detailDemiPension' => $offre->isBdemiPension() ? $offre->getDetailDemiPension() : null,
            'bpensionComplete' => $offre->isBpensionComplete(),
            'prixCompletePension' => $offre->isBpensionComplete() ? $offre->getPrixCompletePension() : null,
            'detailCompletePension' => $offre->isBpensionComplete() ? $offre->getDetailCompletePension() : null,
            'bvisiteMedine' => $offre->isBvisiteMedine(),
            'prix' => $offre->getPrixUn(),
            'prixDouble' => $offre->getPrixDouble(),
            'prixTriple' => $offre->getPrixTriple(),
            'prixQuad' => $offre->getPrixQuad(),
            'prixQuint' => $offre->getPrixQuint(),
            'bcoupCoeur' => $offre->isBcoupCoeur(),
            'bpubier' => $offre->isBpubier(),
            'datePublication' => (!empty($offre->getDatePublication()) ? $offre->getDatePublication()->format('Y-m-d') : null),
            'dateFinPublication' => (!empty($offre->getDateFinPublication()) ? $offre->getDateFinPublication()->format('Y-m-d') : null),
            'bpassport' => $offre->isBpassport(),
            'bphotos' => $offre->isBphotos(),
            'bpassVacinial' => $offre->isBpassVacinial(),
            'detailVoyage' => $offre->getDetailVoyage(),
            'detailVols' => $offre->getDetailVols(),
            'image' => $offre->getImage(),
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
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->addOffre($offre);
        }
        return false;
    }

    public function updateOffre($offre) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $json = [
            'idDestination' =>  '/api/destinations/'.$offre->getIdDestination()->getId(),
            'titre' => $offre->getTitre(),
            'dateDepart' => $offre->getDateDepart()->format('Y-m-d\TH:i:sP'),
            'dateRetour' => $offre->getDateRetour()->format('Y-m-d\TH:i:sP'),
            'ballerRetour' => $offre->isBallerrEtour(),
            'bhebergement' => $offre->isBhebergement(),
            'bvisa' => $offre->isBvisa(),
            'bpetitDejeuner' => $offre->isBpetitDejeuner(),
            'bdemiPension' => $offre->isBdemiPension(),
            'prixDemiPension' => $offre->isBdemiPension() ? $offre->getPrixDemiPension() : null,
            'detailDemiPension' => $offre->isBdemiPension() ? $offre->getDetailDemiPension() : null,
            'bpensionComplete' => $offre->isBpensionComplete(),
            'prixCompletePension' => $offre->isBpensionComplete() ? $offre->getPrixCompletePension() : null,
            'detailCompletePension' => $offre->isBpensionComplete() ? $offre->getDetailCompletePension() : null,
            'bvisiteMedine' => $offre->isBvisiteMedine(),
            'prixDouble' => $offre->getPrixDouble(),
            'prixTriple' => $offre->getPrixTriple(),
            'prixQuad' => $offre->getPrixQuad(),
            'prixQuint' => $offre->getPrixQuint(),
            'bcoupCoeur' => $offre->isBcoupCoeur(),
            'bcoupCoeur' => $offre->isBcoupCoeur(),
            'bpubier' => $offre->isBpubier(),
            'datePublication' => (!empty($offre->getDatePublication()) ? $offre->getDatePublication()->format('Y-m-d') : null),
            'dateFinPublication' => (!empty($offre->getDateFinPublication()) ? $offre->getDateFinPublication()->format('Y-m-d') : null),
            'bpassport' => $offre->isBpassport(),
            'bphotos' => $offre->isBphotos(),
            'bpassVacinial' => $offre->isBpassVacinial(),
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
        if ($response->getStatusCode() === 200) {
            foreach ($offre->getHotels() as $key => $value) {
                $this->hotelApiService->UpdateHotel($value);
            }
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->updateOffre($offre);
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
        if ($response->getStatusCode() === 200) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->DeleteOffre($id);
        }
        return false;
    }
}