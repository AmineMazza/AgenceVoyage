<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CallApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client,TokenStorageInterface $tokenStorage){
        $this->tokenStorage = $tokenStorage;
    }

    public function getJWTToken($password) : array
    {
        $user = $this->getUser();
        $response = $this->client->request('GET', 'http://127.0.0.1/api/login_check', [
            'json' => [
                'email' => $user->getUserIdentifier(),
                'password' => $password
            ]
        ]);
        return $response->toArray();
    }
}