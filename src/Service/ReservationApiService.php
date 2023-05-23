<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Reservation;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReservationApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage, private OffreApiService $offreApiService, private CallApiService $callApiService, private CommercialApiService $commercialApiService, private UserApiService $userApiService){
        $this->tokenStorage = $tokenStorage;
    }

    public function getReservations($params = null) : ArrayCollection
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/reservations',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
                'query' => $params,
            ],
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getReservations();
        }
        $reservations = new ArrayCollection();
        foreach ($response->toArray() as $key => $data) {
            // dd($data);
            $reservation = new Reservation();
            $reservation->setId($data['id']);
            $reservation->setDateReservation(\DateTime::createFromFormat('Y-m-d\TH:i:sP',$data['date_reservation']));
            $reservation->setNumVoyageurs($data['num_voyageurs']);
            $reservation->setRemarque((!empty($data['remarque']) ? $data['remarque'] : null));
            $reservation->setMntCommission((!empty($data['Mnt_commission']) ? $data['Mnt_commission'] : null));
            $reservation->setAvanceCommission((!empty($data['avance_commission']) ? $data['avance_commission'] : null));
            $reservation->setDateAvanceCommission((!empty($data['date_avance_commission']) ? (\DateTime::createFromFormat('Y-m-d\TH:i:sP',$data['date_avance_commission'])) : null));
            $arr_param = explode('/',$data['idOffre']);
            $reservation->setIdOffre($this->offreApiService->getOffre($arr_param[count($arr_param)-1]));
            $arr_param = explode('/',$data['idUser']);
            $reservation->setIdUser($this->userApiService->getOneUser($arr_param[count($arr_param)-1]));
            if(!empty($data['idCommercial'])){
                $arr_param = explode('/',$data['idCommercial']);
                $reservation->setIdCommercial($this->commercialApiService->getCommercial($arr_param[count($arr_param)-1]));
            }
            // dd($reservation);
            $reservations->add($reservation);
        }
        return $reservations;
    }

    public function getReservation($id) : Reservation
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/reservations/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getReservation($id);
        }
        $reservation = new Reservation();
        $data = json_decode($response->getContent());
        $reservation->setId($data->id);
        $reservation->setDateReservation(\DateTime::createFromFormat('Y-m-d\TH:i:sP',$data->date_reservation));
        $reservation->setNumVoyageurs($data->num_voyageurs);
        $reservation->setRemarque((!empty($data->remarque) ? $data->remarque : null));
        $reservation->setMntCommission((!empty($data->Mnt_commission) ? $data->Mnt_commission : null));
        $reservation->setAvanceCommission((!empty($data->avance_commission) ? $data->avance_commission : null));
        $reservation->setDateAvanceCommission((!empty($data->date_avance_commission) ? \DateTime::createFromFormat('Y-m-d\TH:i:sP',$data->date_avance_commission) : null));
        $arr_param = explode('/',$data->idOffre);
        $reservation->setIdOffre($this->offreApiService->getOffre($arr_param[count($arr_param)-1]));
        $arr_param = explode('/',$data->idUser);
        $reservation->setIdUser($this->userApiService->getOneUser($arr_param[count($arr_param)-1]));
        if(!empty($data->idCommercial)){
            $arr_param = explode('/',$data->idCommercial);
            $reservation->setIdCommercial($this->commercialApiService->getCommercial($arr_param[count($arr_param)-1]));
        }
        return $reservation;
    }

    public function AddReservation($reservation, $idO, $idC = null)
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $data = [
            'idOffre' => '/api/offres/'.$idO,
            'idUser' => '/api/users/'. $this->getUser()->getId(),
            'dateReservation' => (new \DateTime())->format('Y-m-d\TH:i:sP'),
            'numVoyageurs' => $reservation->getNumVoyageurs(),
            'remarque' => $reservation->getRemarque(),
            'mntCommission' => $reservation->getMntCommission(),
            'avanceCommission' => $reservation->getAvanceCommission(),
            'dateAvanceCommission' => (($reservation->getAvanceCommission()!=null) ? (new \DateTime())->format('Y-m-d\TH:i:sP') : null ),
        ];
        if($idC != null) $data['idCommercial'] = '/api/commercials/'.$idC;
        $response = $this->client->request('POST', 'http://127.0.0.1/api/reservations', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => $data,
        ]);
        if ($response->getStatusCode() === 201) {
            $foreignKeyResponseData = json_decode($response->getContent(), true);
            $foreignKeyId = $foreignKeyResponseData['id'];
            return $foreignKeyId;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->AddReservation($reservation, $idO, $idC);
        }
        return null;
    }

    public function UpdateReservation($reservation) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/reservations/'.$reservation->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'numVoyageurs' => $reservation->getNumVoyageurs(),
                'remarque' => $reservation->getRemarque(),
                'mntCommission' => $reservation->getMntCommission(),
                'avanceCommission' => $reservation->getAvanceCommission(),
                'dateAvanceCommission' => (($reservation->getAvanceCommission()!=null) ? (new \DateTime())->format('Y-m-d\TH:i:sP') : null ),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->UpdateReservation($reservation);
        }
        return false;
    }

    public function DeleteReservation($id) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('DELETE', 'http://127.0.0.1/api/reservations/'.$id,[
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
            $this->DeleteReservation($id);
        }
        return false;
    }
}