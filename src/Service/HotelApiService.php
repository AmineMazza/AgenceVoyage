<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Hotel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HotelApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage, private CallApiService $callApiService){
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
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getHotels();
        }
        return $response->toArray();
    }

    public function getHotelsParams($params) : array
    {
        $response = $this->client->request('GET', 'http://127.0.0.1/api/hotels',[
            'headers' => [
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
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getHotel($id);
        }
        $hotel = new Hotel();
        $data = json_decode($response->getContent());
        $hotel->setId($data->id);
        $hotel->setIdOffre($data->idOffre);
        $hotel->setLieu($data->lieu);
        $hotel->setEtoile($data->etoile);
        $hotel->setDistance($data->distance);
        $hotel->setNombreNuits($data->nombreNuits);
        $hotel->setName($data->name);
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
                'name'  => $hotel->getName(),
            ],
        ]);
        if ($response->getStatusCode() === 201) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->AddHotel($hotel,$idOfre);
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
                'lieu' => $hotel->getLieu(),
                'etoie' => $hotel->getEtoile(),
                'distance' => $hotel->getDistance(),
                'nombreNuits' => $hotel->getNombreNuits(),
                'name'  => $hotel->getName(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->UpdateHotel($hotel);
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
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->DeleteHotel($id);
        }
        return false;
    }
}