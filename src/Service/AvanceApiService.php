<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Avance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AvanceApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage){
        $this->tokenStorage = $tokenStorage;
    }

    public function getAvances() : array
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/avances',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        return $response->toArray();
    }

    public function getAvance($id) : Avance
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/avances/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        $avance = new Avance();
        $data = json_decode($response->getContent());
        $avance->setId($data->id);
        $avance->setMontant($data->montant);
        $avance->setRefRecu($data->ref_recu);
        $avance->setDate($data->date);
        $avance->setIdReservation($data->idReservation);
        return $avance;
    }

    public function AddAvance($avance) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('POST', 'http://127.0.0.1/api/Avances', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'idReservation' => '/api/reservations/'.$avance->getIdResevarion()->getId(),
                'date' => $avance->getDate()->format('Y-m-d\TH:i:sP'),
                'montant' => $avance->getMontant(),
                'refRecu' => $avance->getRefRecu(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        return false;
    }

    public function UpdateAvance($avance) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/Avances/'.$avance->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'idReservation' => '/api/reservations/'.$avance->getIdResevarion()->getId(),
                'date' => $avance->getDate()->format('Y-m-d\TH:i:sP'),
                'montant' => $avance->getMontant(),
                'refRecu' => $avance->getRefRecu(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        return false;
    }

    public function DeleteAvance($id) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('DELETE', 'http://127.0.0.1/api/Avances/'.$id,[
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