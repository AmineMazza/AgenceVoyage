<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Voyageur;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class VoyageurApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage, private CallApiService $callApiService){
        $this->tokenStorage = $tokenStorage;
    }

    public function getVoyageurs($idR) : ArrayCollection
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/voyageurs',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'query' => [
                'id_reservation' => $idR,
            ]
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getVoyageurs($idR);
        }
        $voyageurs = new ArrayCollection();
        foreach ($response->toArray() as $key => $data) {
            // dd($data);
            $voyageur = new Voyageur();
            $voyageur->setId($data['id']);
            $voyageur->setNom((!empty($data['nom']) ? $data['nom'] : null));
            $voyageur->setPrenom((!empty($data['prenom']) ? $data['prenom'] : null));
            $voyageur->setCin((!empty($data['cin']) ? $data['cin'] : null));
            $voyageur->setEmail((!empty($data['email']) ? $data['email'] : null));
            $voyageur->setAdresse((!empty($data['adresse']) ? $data['adresse'] : null));
            $voyageur->setTelephone((!empty($data['telephone']) ? $data['telephone'] : null));
            $voyageur->setNumPassport((!empty($data['numPassport']) ? $data['numPassport'] : null));
            $voyageur->setDateNaissance((!empty($data['dateNaissance']) ? $data['dateNaissance'] : null));
            $voyageurs->add($voyageur);
        }
        return $voyageurs;
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
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getVoyageur($id);
        }
        $voyageur = new Voyageur();
        $data = json_decode($response->getContent());
        $voyageur->setId($data->id);
        $voyageur->setNom((!empty($data->nom) ? $data->nom : null));
        $voyageur->setPrenom((!empty($data->prenom) ? $data->prenom : null));
        $voyageur->setCin((!empty($data->cin) ? $data->cin : null));
        $voyageur->setEmail((!empty($data->email) ? $data->email : null));
        $voyageur->setAdresse((!empty($data->adresse) ? $data->adresse : null));
        $voyageur->setTelephone((!empty($data->telephone) ? $data->telephone : null));
        $voyageur->setNumPassport((!empty($data->numPassport) ? $data->numPassport : null));
        $voyageur->setDateNaissance((!empty($data->dateNaissance) ? $data->dateNaissance : null));
        return $voyageur;
    }

    public function AddVoyageur($voyageur,$idR) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('POST', 'http://127.0.0.1/api/voyageurs', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'idReservation' => '/api/reservations/'.$idR,
                'nom' => $voyageur->getNom(),
                'prenom' => $voyageur->getPrenom(),
                'cin' => $voyageur->getCin(),
                'email' => $voyageur->getEmail(),
                'adresse' => $voyageur->getAdresse(),
                'telephone' => $voyageur->getTelephone(),
                'numPassport' => $voyageur->getNumPassport(),
                'dateNaissance' => $voyageur->getDateNaissance(),
            ],
        ]);
        if ($response->getStatusCode() === 201) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->AddVoyageur($voyageur,$idR);
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
                'nom' => $voyageur->getNom(),
                'prenom' => $voyageur->getPrenom(),
                'cin' => $voyageur->getCin(),
                'email' => $voyageur->getEmail(),
                'adresse' => $voyageur->getAdresse(),
                'telephone' => $voyageur->getTelephone(),
                'numPassport' => $voyageur->getNumPassport(),
                'dateNaissance' => $voyageur->getDateNaissance(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->UpdateVoyageur($voyageur);
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
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->DeleteVoyageur($id);
        }
        return false;
    }
}