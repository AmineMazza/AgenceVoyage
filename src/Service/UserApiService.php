<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage, private CallApiService $callApiService, private AgentApiService $agentApiService){
        $this->tokenStorage = $tokenStorage;
    }

    public function getUsers() : ArrayCollection
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/users',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ]
        ]);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getUsers();
        }
        $Users = new ArrayCollection();
        foreach ($response->toArray() as $key => $data) {
            // dd($data);
            $User = new User();
            $User->setId($data['id']);
            $User->setEmail((!empty($data['email']) ? $data['email'] : null));
            $User->setPassword((!empty($data['password']) ? $data['password'] : null));
            // $User->setRoles((!empty($data['prenom']) ? $data['prenom'] : null));
            // if(!empty($data->agent)){
            //     $arr_param = explode('/',$data->agent);
            //     $User->setAgent($this->agentApiService->getAgent($arr_param[count($arr_param)-1]));
            // }
            $Users->add($User);
        }
        return $Users;
    }

    public function getOneUser($id) : User
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/users/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        // dd($response);
        if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->getOneUser($id);
        }
        $User = new User();
        $data = json_decode($response->getContent());
        // dd($data);
        $User = new User();
        $User->setId($data->id);
        $User->setEmail((!empty($data->email) ? $data->email : null));
        $User->setPassword((!empty($data->password) ? $data->password : ''));
        $User->setRoles((!empty($data->roles) ? $data->roles : []));
        // if(!empty($data->agent)){
        //     $arr_param = explode('/',$data->agent);
        //     $User->setAgent($this->agentApiService->getAgent($arr_param[count($arr_param)-1]));
        // }
        return $User;
    }

    public function AddUser($user) : bool
    {
        // dd($user->getEmail());
        $response = $this->client->request('POST', 'http://127.0.0.1/api/users', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'json' => [
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'password' => $user->getPassword(),
            ],
        ]);
        // dd($response);
        if ($response->getStatusCode() === 201) {
            $foreignKeyResponseData = json_decode($response->getContent(), true);
            $foreignKeyId = $foreignKeyResponseData['id'];
            $this->agentApiService->AddAgent($user->getAgent(),$foreignKeyId);
            return true;
        }
        return false;
    }

    public function UpdateUser($User) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/users/'.$User->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'email' => $User->getEmail(),
                'roles' => $User->getRoles(),
                'password' => $User->getPassword(),
            ],
        ]);
        if ($response->getStatusCode() === 201) {
            return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->UpdateUser($User);
        }
        return false;
    }

    public function DeleteUser($id) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('DELETE', 'http://127.0.0.1/api/users/'.$id,[
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
            $this->DeleteUser($id);
        }
        return false;
    }

    public function ValidAgent($idU,$idA) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/users/'.$idU, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'roles' => ['ROLE_AGENT'],
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            $bool = $this->agentApiService->VaildAgent($idA);
            if($bool) return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->ValidAgent($idU,$idA);
        }
        return false;
    }

    public function UnvalidAgent($idU,$idA) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/users/'.$idU, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => [
                'roles' => ['ROLE_USER'],
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            $bool = $this->agentApiService->UnvaildAgent($idA);
            if($bool) return true;
        }
        else if ($response->getStatusCode() === 401) {
            $this->callApiService->getJWTRefreshToken();
            $this->UnvalidAgent($idU,$idA);
        }
        return false;
    }
}