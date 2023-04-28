<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Reservation;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReservationApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage, private OffreApiService $offreApiService){
        $this->tokenStorage = $tokenStorage;
    }

    public function getReservations() : ArrayCollection
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/reservations',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        $reservations = new ArrayCollection();
        foreach ($response->toArray() as $key => $data) {
            dd($data);
            $reservation = new Reservation();
            $reservation->setId($data->id);
            $reservation->setDateReservation($data->date_reservation);
            $reservation->setNumVoyageurs($data->num_voyageurs);
            $reservation->setRemarque($data->remarque);
            $reservation->setMntCommission($data->Mnt_commission);
            $reservation->setAvanceCommission($data->avance_commission);
            $reservation->setDateAvanceCommission($data->date_avance_commission);
            $arr_param = explode('/',$data->idOffre);
            $reservation->setIdOffre($this->offreApiService->getOffre($arr_param[count($arr_param)-1]));
            $arr_param = explode('/',$data->idUser);
            // $reservation->setIdUser($data->idUser);
            $reservation->setIdCommercial($data->idCommercial);
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
        $reservation = new Reservation();
        $data = json_decode($response->getContent());
        $reservation->setId($data->id);
        $reservation->setIdOffre($data->idOffre);
        $reservation->setIdUser($data->idUser);
        $reservation->setIdCommercial($data->idCommercial);
        $reservation->setDateReservation($data->date_reservation);
        $reservation->setNumVoyageurs($data->num_voyageurs);
        $reservation->setRemarque($data->remarque);
        $reservation->setMntCommission($data->Mnt_commission);
        $reservation->setAvanceCommission($data->avance_commission);
        $reservation->setDateAvanceCommission($data->date_avance_commission);
        return $reservation;
    }

    public function AddReservation($reservation) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('POST', 'http://127.0.0.1/api/reservations', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'idOffre' => '/api/offres/'.$reservation->getIdOffre()->getId(),
                'idUser' => '/api/users/'.$reservation->getIdUser()->getId(),
                'idCommercial' => '/api/commercials/'.$reservation->getIdCommercial()->getId(),
                'dateReservation' => $reservation->getDateReservation(),
                'numVoyageurs' => $reservation->getNumVoyageurs(),
                'remarque' => $reservation->getRemarque(),
                'mntCommission' => $reservation->getMntCommission(),
                'avanceCommission' => $reservation->getAvanceCommission(),
                'dateAvanceCommission' => $reservation->getDateAvanceCommission(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        return false;
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
                'idOffre' => '/api/offres/'.$reservation->getIdOffre()->getId(),
                'idUser' => '/api/users/'.$reservation->getIdUser()->getId(),
                'idCommercial' => '/api/commercials/'.$reservation->getIdCommercial()->getId(),
                'dateReservation' => $reservation->getDateReservation(),
                'numVoyageurs' => $reservation->getNumVoyageurs(),
                'remarque' => $reservation->getRemarque(),
                'mntCommission' => $reservation->getMntCommission(),
                'avanceCommission' => $reservation->getAvanceCommission(),
                'dateAvanceCommission' => $reservation->getDateAvanceCommission(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
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
        return false;
    }
}