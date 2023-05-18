<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Message;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MessageApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage, private CallApiService $callApiService){
        $this->tokenStorage = $tokenStorage;
    }

    public function getMessages() : array
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/messages',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getMessages();
        }
        return $response->toArray();
    }

    public function getMessage($id) : Message
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/messages/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getMessage($id);
        }
        $message = new Message();
        $data = json_decode($response->getContent());
        $message->setId($data->id);
        $message->setNom($data->nom ? $data->nom : null);
        $message->setEmail($data->email);
        $message->setTelephone($data->telephone ? $data->telephone : null);
        $message->setMessage($data->message);
        $message->setDateEnvoi($data->date_envoi);
        return $message;
    }

    public function AddMessage($message,$idO = null) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $json = [
            'nom' => $message->getNom(),
            'email' => $message->getEmail(),
            'telephone' => $message->getTelephone(),
            'message' => $message->getMessage(),
            'dateEnvoi' => (new \DateTime())->format('Y-m-d\TH:i:sP'),
        ];
        if($idO != null) $json['idOffre'] = '/api/offres/'.$idO;
        $response = $this->client->request('POST', 'http://127.0.0.1/api/messages', [
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
            $this->AddMessage($message,$idO);
        }
        return false;
    }

    public function UpdateMessage($message) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/messages/'.$message->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'nom' => $message->getNom(),
                'email' => $message->getEmail(),
                'telephone' => $message->getTelephone(),
                'message' => $message->getMessage(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->UpdateMessage($message);
        }
        return false;
    }

    public function DeleteMessage($id) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('DELETE', 'http://127.0.0.1/api/messages/'.$id,[
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
            $this->DeleteMessage($id);
        }
        return false;
    }
}