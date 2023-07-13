<?php

namespace App\Service;


use App\Entity\CollectionOffre;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CollectionOffreApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage, private CallApiService $callApiService){
        $this->tokenStorage = $tokenStorage;
    }

    public function getCategorieOffre() : array
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/collection_offres/',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getCategorieOffre();
        }
        return $response->toArray();
    }

    public function getColectionOffre($id) : CollectionOffre
    {
        $response = $this->client->request('GET', 'http://127.0.0.1/api/collection_offres/'.$id, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        $categorie= new CategorieOffre();
        $data = json_decode($response->getContent());
        $categorie->setId($data->id);
        $categorie->setNom($data->pays);
        return $categorie;
    }

    public function AddCategorie($categorie) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('POST', 'http://127.0.0.1/api/collection_offres/', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => ['nom' => $categorie->getNom(),'description'=> $categorie->getDescription()],
        ]);
        if ($response->getStatusCode() === 201) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->AddCategorie($categorie);
        }
        return false;
    }

    public function getCtegorieOffre($id) : CollectionOffre
    {
        $response = $this->client->request('GET', 'http://127.0.0.1/api/collection_offres/'.$id, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        $categorieOffre= new CollectionOffre();
        $data = json_decode($response->getContent());
        $categorieOffre->setNom($data->nom);
        $categorieOffre->setDescription($data->description);
        return $categorieOffre;
    }
    public function UpdateCtegorieOffre($categorieOffre) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/collection_offres/'.$categorieOffre->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => ['nom' => $categorieOffre->getNom(),'description' => $categorieOffre->getDescription()],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->UpdateDestination($categorieOffre);
        }
        return false;
    }

    public function DeleteCtegorieOffre($id) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('DELETE', 'http://127.0.0.1/api/collection_offres/'.$id,[
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
            $this->DeleteCtegorieOffre($id);
        }
        return false;
    }
}