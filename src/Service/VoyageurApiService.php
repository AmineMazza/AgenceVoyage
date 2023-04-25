<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Voyageur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class VoyageurApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage){
        $this->tokenStorage = $tokenStorage;
    }

    public function getVoyageurs() : array
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/voyageurs',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        return $response->toArray();
    }

    public function getVoyageur($id) : Voyageur
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/voyageurs/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        $voyageur = new Voyageur();
        $data = json_decode($response->getContent());
        $voyageur->setId($data->id);
        $voyageur->setIdReservation($data->IdReservation);
        $voyageur->setNom($data->nom);
        $voyageur->setPrenom($data->prenom);
        $voyageur->setCin($data->cin);
        $voyageur->setEmail($data->email);
        $voyageur->setAdresse($data->adresse);
        $voyageur->setTelephone($data->telephone);
        $voyageur->setNumPassport($data->num_passport);
        $voyageur->setDateNaissance($data->date_naissance);
        return $voyageur;
    }

    public function AddVoyageur($voyageur) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('POST', 'http://127.0.0.1/api/voyageurs', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'idReservation' => '/api/reservations/'.$voyageur->getIdReservation()->getId(),
                'nom' => $voyageur->setNom(),
                'prenom' => $voyageur->setPrenom(),
                'cin' => $voyageur->setCin(),
                'email' => $voyageur->setEmail(),
                'adresse' => $voyageur->setAdresse(),
                'telephone' => $voyageur->setTelephone(),
                'numPassport' => $voyageur->setNumPassport(),
                'dateNaissance' => $voyageur->setDateNaissance(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        return false;
    }

    public function UpdateVoyageur($voyageur) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/voyageurs/'.$voyageur->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'idReservation' => '/api/reservations/'.$voyageur->getIdReservation()->getId(),
                'nom' => $voyageur->setNom(),
                'prenom' => $voyageur->setPrenom(),
                'cin' => $voyageur->setCin(),
                'email' => $voyageur->setEmail(),
                'adresse' => $voyageur->setAdresse(),
                'telephone' => $voyageur->setTelephone(),
                'numPassport' => $voyageur->setNumPassport(),
                'dateNaissance' => $voyageur->setDateNaissance(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        return false;
    }

    public function DeleteVoyageur($id) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('DELETE', 'http://127.0.0.1/api/voyageurs/'.$id,[
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