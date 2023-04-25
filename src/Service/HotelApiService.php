<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Hotel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HotelApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage){
        $this->tokenStorage = $tokenStorage;
    }

    public function getHotels() : array
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/hotels',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        return $response->toArray();
    }

    public function getHotelsParams($params) : array
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/hotels',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'query' => $params
        ]);
        return json_decode($response->getContent());
    }

    public function getHotel($id) : Hotel
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/hotels/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            
        ]);
        $hotel = new Hotel();
        $data = json_decode($response->getContent());
        $hotel->setId($data->id);
        $hotel->setIdOffre($data->idOffre);
        $hotel->setLieu($data->lieu);
        $hotel->setEtoile($data->etoile);
        $hotel->setDistance($data->distance);
        $hotel->setNombreNuits($data->nombreNuits);
        return $hotel;
    }

    public function AddHotel($hotel,$idOfre) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('POST', 'http://127.0.0.1/api/hotels', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'idOffre' => '/api/offres/'.$idOfre,
                'lieu' => $hotel->getLieu(),
                'etoile' => $hotel->getEtoile(),
                'distance' => $hotel->getDistance(),
                'nombreNuits' => $hotel->getNombreNuits(),
            ],
        ]);
        if ($response->getStatusCode() === 201) {
            return true;
        }
        return false;
    }

    public function UpdateHotel($hotel) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/hotels/'.$hotel->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'idOffre' => '/api/offres/'.$hotel->getIdOffre()->getId(),
                'lieu' => $hotel->getLieu(),
                'etoie' => $hotel->getEtoile(),
                'distance' => $hotel->getDistance(),
                'nombreNuits' => $hotel->getNombreNuits(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        return false;
    }

    public function DeleteHotel($id) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('DELETE', 'http://127.0.0.1/api/hotels/'.$id,[
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