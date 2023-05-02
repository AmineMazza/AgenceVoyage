<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Agent;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AgentApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage, private CallApiService $callApiService){
        $this->tokenStorage = $tokenStorage;
    }

    public function getAgents() : ArrayCollection
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/agents',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getAgents();
        }
        $Agents = new ArrayCollection();
        foreach ($response->toArray() as $key => $data) {
            // dd($data);
            $Agent = new Agent();
            $Agent->setId($data['id']);
            $Agent->setAgence((!empty($data['agence']) ? $data['agence'] : null));
            $Agent->setNom((!empty($data['nom']) ? $data['nom'] : null));
            $Agent->setPrenom((!empty($data['prenom']) ? $data['prenom'] : null));
            $Agent->setTelephoneMobile((!empty($data['telephone_mobile']) ? $data['telephone_mobile'] : null));
            $Agent->setTelephoneFixe((!empty($data['telephone_fixe']) ? $data['telephone_fixe'] : null));
            $Agent->setAdresse((!empty($data['adresse']) ? $data['adresse'] : null));
            $Agent->setAbonnememt((!empty($data['abonnememt']) ? $data['abonnememt'] : null));
            $Agent->setBstatus((!empty($data['bstatus']) ? $data['bstatus'] : null));
            $Agent->setLogo((!empty($data['logo']) ? $data['logo'] : null));
            $Agents->add($Agent);
        }
        return $Agents;
    }

    public function getAgent($id) : Agent
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/agents/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getAgent($id);
        }
        $Agent = new Agent();
        $data = json_decode($response->getContent());
        $Agent->setId($data['id']);
        $Agent->setAgence((!empty($data['agence']) ? $data['agence'] : null));
        $Agent->setNom((!empty($data['nom']) ? $data['nom'] : null));
        $Agent->setPrenom((!empty($data['prenom']) ? $data['prenom'] : null));
        $Agent->setTelephoneMobile((!empty($data['telephone_mobile']) ? $data['telephone_mobile'] : null));
        $Agent->setTelephoneFixe((!empty($data['telephone_fixe']) ? $data['telephone_fixe'] : null));
        $Agent->setAdresse((!empty($data['adresse']) ? $data['adresse'] : null));
        $Agent->setAbonnememt((!empty($data['abonnememt']) ? $data['abonnememt'] : null));
        $Agent->setBstatus((!empty($data['bstatus']) ? $data['bstatus'] : null));
        $Agent->setLogo((!empty($data['logo']) ? $data['logo'] : null));
        return $Agent;
    }

    public function AddAgent($Agent) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('POST', 'http://127.0.0.1/api/agents', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'idUser' => '/api/users/'.$this->getUser()->getId(),
                'nom' => $Agent->getId(),
                'agence' => $Agent->getAgence(),
                'nom' => $Agent->getNom(),
                'prenom' => $Agent->getPrenom(),
                'telephoneMobile' => $Agent->getTelephoneMobile(),
                'telephoneFixe' => $Agent->getTelephoneFixe(),
                'adresse' => $Agent->getAdresse(),
                'abonnement' => $Agent->getAbonnememt(),
                'bstatus' => $Agent->getBstatus(),
                'logo' => $Agent->getLogo(),
            ],
        ]);
        if ($response->getStatusCode() === 201) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->AddAgent($Agent,$idR);
        }
        return false;
    }

    public function UpdateAgent($Agent) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/agents/'.$Agent->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'nom' => $Agent->getId(),
                'agence' => $Agent->getAgence(),
                'nom' => $Agent->getNom(),
                'prenom' => $Agent->getPrenom(),
                'telephoneMobile' => $Agent->getTelephoneMobile(),
                'telephoneFixe' => $Agent->getTelephoneFixe(),
                'adresse' => $Agent->getAdresse(),
                'abonnement' => $Agent->getAbonnememt(),
                'bstatus' => $Agent->getBstatus(),
                'logo' => $Agent->getLogo(),
            ],
        ]);
        if ($response->getStatusCode() === 201) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->UpdateAgent($Agent);
        }
        return false;
    }

    public function DeleteAgent($id) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('DELETE', 'http://127.0.0.1/api/agents/'.$id,[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 201) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->DeleteAgent($id);
        }
        return false;
    }
}