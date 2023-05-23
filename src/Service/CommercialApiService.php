<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Commercial;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class CommercialApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage, private CallApiService $callApiService){
        $this->tokenStorage = $tokenStorage;
    }

    public function getCommercials() : ArrayCollection
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/commercials?id_user='.$this->getUser()->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        // dd($response->toArray());
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getCommercials();
        }
        $commercials = new ArrayCollection();
        foreach ($response->toArray() as $key => $data) {
            // dd($data);
            $commercial = new Commercial();
            $commercial->setId($data['id']);
            $commercial->setNom($data['nom']);
            $commercial->setPrenom($data['prenom']);
            $commercial->setAdresse((!empty($data['adresse']) ? $data['adresse'] : null));
            $commercial->setTelephone($data['telephone']);
            $commercial->setCin($data['cin']);
            $commercials->add($commercial);
        }
        return $commercials;
    }

    public function getCommercialsAgent($idA) : ArrayCollection
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");

        $response = $this->client->request('GET', 'http://127.0.0.1/api/commercials?agents='.$idA, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getCommercialsAgent($idA);
        }
        $commercials = new ArrayCollection();
        foreach ($response->toArray() as $key => $data) {
            // dd($data);
            $commercial = new Commercial();
            $commercial->setId($data['id']);
            $commercial->setNom($data['nom']);
            $commercial->setPrenom($data['prenom']);
            $commercial->setAdresse((!empty($data['adresse']) ? $data['adresse'] : null));
            $commercial->setTelephone($data['telephone']);
            $commercial->setCin($data['cin']);
            $commercials->add($commercial);
        }
        return $commercials;
    }

    public function getCommercialsJSON()
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/commercials',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getCommercials();
        }
        return $response->getContent();
    }

    public function getCommercial($id) : Commercial
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/commercials/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getCommercial($id);
        }
        $commercial = new Commercial();
        $data = json_decode($response->getContent());
        $commercial->setId($data->id);
        $commercial->setNom($data->nom);
        $commercial->setPrenom($data->prenom);
        $commercial->setAdresse((!empty($data->adresse) ? $data->adresse : null));
        $commercial->setTelephone($data->telephone);
        $commercial->setCin($data->cin);
        return $commercial;
    }

    public function getCommercialCin($cin)
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/commercials/', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'query' => ['cin' => $cin],
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getCommercialCin($cin);
        }
        $data = json_decode($response->getContent());
        return $data[0];
    }

    public function AddCommercial($commercial)
    {
        $json = [
            'nom' => $commercial->getNom(),
            'prenom' => $commercial->getPrenom(),
            'cin' => $commercial->getCin(),
            'adresse' => $commercial->getAdresse(),
            'telephone' => $commercial->getTelephone(),
        ];
        if($this->isGranted('ROLE_AGENT')){
            $json['agents'] = ['api/agents/'.$this->getUser()->getAgent()->getId()];
        }
        else if($this->isGranted('ROLE_ADMIN')){
            $json['idUser'] = 'api/users/'.$this->getUser()->getId();
        }
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('POST', 'http://127.0.0.1/api/commercials', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => $json,
        ]);
        $data = $response->toArray(false);
        if(array_key_exists('detail',$data)){
            if(str_contains($data['detail'],'UNIQ_7653F3AEABE530DA')){
                $com = $this->getCommercialCin($commercial->getCin());
                $this->AddCommercialUnique($com);
            }
        }
        if ($response->getStatusCode() === 201) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->AddCommercial($commercial);
        }
        return false;
    }

    public function AddCommercialUnique($commercial)
    {
        if($this->isGranted('ROLE_AGENT')){
            $count = count($commercial->agents);
            $commercial->agents[$count] = 'api/agents/'.$this->getUser()->getAgent()->getId();
            $json = ['agents' => $commercial->agents,];
        }
        else if($this->isGranted('ROLE_ADMIN')){
            $json = ['idUser' => 'api/users/'.$this->getUser()->getId()];
        }
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/commercials/'.$commercial->id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => $json,
        ]);
        if ($response->getStatusCode() === 201) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->AddCommercialUnique($commercial);
        }
        return false;
    }



    public function AddCommercialAgent($idC,$idA)
    {
        $commercial = $this->getCommercialJSON($idC);
        $count = count($commercial->agents);
        $commercial->agents[$count] = 'api/agents/'.$idA;
        $json = ['agents' => $commercial->agents];
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/commercials/'.$commercial->id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => $json,
        ]);
        if ($response->getStatusCode() === 201) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->AddCommercialAgent($commercial,$idA);
        }
        return false;
    }

    public function getCommercialJSON($id)
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/commercials/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getCommercialJSON($id);
        }
        return json_decode($response->getContent());
    }

    public function UpdateCommercial($commercial) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/commercials/'.$commercial->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'nom' => $commercial->getNom(),
                'prenom' => $commercial->getPrenom(),
                'adresse' => $commercial->getAdresse(),
                'telephone' => $commercial->getTelephone(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->UpdateCommercial($commercial);
        }
        return false;
    }

    public function DeleteCommercial($id) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('DELETE', 'http://127.0.0.1/api/commercials/'.$id,[
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
            $this->DeleteCommercial($id);
        }
        return false;
    }
}