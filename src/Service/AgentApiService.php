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
        $agents = new ArrayCollection();
        foreach ($response->toArray() as $key => $data) {
            // dd($data);
            $agent = new Agent();
            $agent->setId($data['id']);
            $agent->setAgence((!empty($data['agence']) ? $data['agence'] : null));
            $agent->setNom((!empty($data['nom']) ? $data['nom'] : null));
            $agent->setPrenom((!empty($data['prenom']) ? $data['prenom'] : null));
            $agent->setTelephoneMobile((!empty($data['telephone_mobile']) ? $data['telephone_mobile'] : null));
            $agent->setTelephoneFixe((!empty($data['telephone_fixe']) ? $data['telephone_fixe'] : null));
            $agent->setAdresse((!empty($data['adresse']) ? $data['adresse'] : null));
            $agent->setAbonnement((!empty($data['abonnement']) ? $data['abonnement'] : null));
            $agent->setBstatus((!empty($data['bstatus']) ? $data['bstatus'] : null));
            $agent->setLogo((!empty($data['logo']) ? $data['logo'] : null));
            $agents->add($agent);
        }
        return $agents;
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
        $agent = new Agent();
        $data = json_decode($response->getContent());
        $agent->setId($data['id']);
        $agent->setAgence((!empty($data['agence']) ? $data['agence'] : null));
        $agent->setNom((!empty($data['nom']) ? $data['nom'] : null));
        $agent->setPrenom((!empty($data['prenom']) ? $data['prenom'] : null));
        $agent->setTelephoneMobile((!empty($data['telephone_mobile']) ? $data['telephone_mobile'] : null));
        $agent->setTelephoneFixe((!empty($data['telephone_fixe']) ? $data['telephone_fixe'] : null));
        $agent->setAdresse((!empty($data['adresse']) ? $data['adresse'] : null));
        $agent->setAbonnement((!empty($data['abonnement']) ? $data['abonnement'] : null));
        $agent->setBstatus((!empty($data['bstatus']) ? $data['bstatus'] : null));
        $agent->setLogo((!empty($data['logo']) ? $data['logo'] : null));
        return $agent;
    }

    public function AddAgent($agent,$idU) : bool
    {
        $response = $this->client->request('POST', 'http://127.0.0.1/api/agents', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'json' => [
                'idUser' => 'api/users/'.$idU,
                'agence' => $agent->getAgence(),
                'nom' => $agent->getNom(),
                'prenom' => $agent->getPrenom(),
                'telephoneMobile' => $agent->getTelephoneMobile(),
                'telephoneFixe' => $agent->getTelephoneFixe(),
                'adresse' => $agent->getAdresse(),
                'abonnement' => $agent->getAbonnement(),
                'bstatus' => (($agent->isBstatus() != null) ? $agent->isBstatus() : false),
                'logo' => $agent->getLogo(),
            ],
        ]);
        if ($response->getStatusCode() === 201) {
            return true;
        }
        return false;
    }

    public function UpdateAgent($agent) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/agents/'.$agent->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'nom' => $agent->getId(),
                'agence' => $agent->getAgence(),
                'nom' => $agent->getNom(),
                'prenom' => $agent->getPrenom(),
                'telephoneMobile' => $agent->getTelephoneMobile(),
                'telephoneFixe' => $agent->getTelephoneFixe(),
                'adresse' => $agent->getAdresse(),
                'abonnement' => $agent->getAbonnement(),
                'bstatus' => (($agent->isBstatus() != null) ? $agent->isBstatus() : false),
                'logo' => $agent->getLogo(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->UpdateAgent($agent);
        }
        return false;
    }

    public function DeleteAgent($agent) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('DELETE', 'http://127.0.0.1/api/agents/'.$agent->getId(),[
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
            $this->DeleteAgent($agent);
        }
        return false;
    }

    public function VaildAgent($idA) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/agents/'.$idA, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'bstatus' => true,
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->VaildAgent($idA);
        }
        return false;
    }

    public function UnvaildAgent($idA) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/agents/'.$idA, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'bstatus' => false,
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->UnvaildAgent($idA);
        }
        return false;
    }
}