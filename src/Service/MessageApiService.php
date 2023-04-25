<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MessageApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage){
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
        $message = new Message();
        $data = json_decode($response->getContent());
        $message->setId($data->id);
        $message->setIdOffre($data->idOffre);
        $message->setNom($data->nom);
        $message->setEmail($data->email);
        $message->setMessage($data->distance);
        $message->setDateEnvoi($data->date_envoi);
        return $message;
    }

    public function AddMessage($message) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('POST', 'http://127.0.0.1/api/messages', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'idOffre' => '/api/offres/'.$message->getIdOffre()->getId(),
                'nom' => $message->getNom(),
                'email' => $message->getEmail(),
                'message' => $message->getMessage(),
                'dateEnvoi' => $message->getDateEnvoi(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
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
                'idOffre' => '/api/offres/'.$message->getIdOffre()->getId(),
                'nom' => $message->getNom(),
                'email' => $message->getEmail(),
                'message' => $message->getMessage(),
                'dateEnvoi' => $message->getDateEnvoi(),
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
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
        return false;
    }
}