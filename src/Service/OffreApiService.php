<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Offre;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OffreApiService extends AbstractController {

    private $tokenStorage;
    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage){
        $this->tokenStorage = $tokenStorage;
    }

    public function getOffres() : array
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/offres',[
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        return $response->toArray();
    }

    public function getOffre($id) : Offre
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('GET', 'http://127.0.0.1/api/offres/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
        ]);
        $offre = new Offre();
        $data = json_decode($response->getContent());
        // $offre->setId($data->id);
        // $offre->setPays($data->pays);
        return $offre;
    }

    public function addOffre($offre) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        // dd($offre);
        $user = $this->getUser();
        $json = [ 
            'id_user' => [
                'id' => $user->getId(),
            ],
            'id_destination' =>  $offre->getIdDestination()->getId(),
            'titre' => $offre->getTitre(),
            'dateDepart' => $offre->getDateDepart()->format('Y-m-d\TH:i:sP'),
            'dateRetour' => $offre->getDateRetour()->format('Y-m-d\TH:i:sP'),
            'ballerRetour' => $offre->isBallerrEtour(),
            'bhebergement' => $offre->isBhebergement(),
            'bvisa' => $offre->isBvisa(),
            'bpetitDejeuner' => $offre->isBpetitDejeuner(),
            'bdemiPension' => $offre->isBdemiPension(),
            'bpensionComplete' => $offre->isBpensionComplete(),
            'bvisiteMedine' => $offre->isBvisiteMedine(),
            'prixChambre' => $offre->getPrixChambre(),
            'prixChambreDouble' => $offre->getPrixChambreDouble(),
            'prixChambreTriple' => $offre->getPrixChambreTriple(),
            'prixChambreQuad' => $offre->getPrixChambreQuad(),
            'prixChambreQuint' => $offre->getPrixChambreQuint(),
            'bcoupCoeur' => $offre->isBcoupCoeur(),
            'bpubier' => $offre->isBpubier(),
            'datePublication' => $offre->getDatePublication(),
            'dateFinPublication' => $offre->getDateFinPublication(),
            'bpassport' => $offre->isBpassport(),
            'bphotos' => $offre->isBphotos(),
            'bpassVacinial' => $offre->isBpassVacinial(),
            'prix' => $offre->getPrix(),
            'detailVoyage' => $offre->getDetailVoyage(),
            'detailVols' => $offre->getDetailVols(),
        ];
        // dd($json);

        $response = $this->client->request('POST', 'http://127.0.0.1/api/offres', [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => $json,
        ]);
        dd($response);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        return false;
    }

    public function updateOffre($offre) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken"); 
        $response = $this->client->request('PUT', 'http://127.0.0.1/api/offres/'.$offre->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json',
            ],
            'json' => ['pays' => $offre->getPays()],
        ]);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        return false;
    }

    public function DeleteOffre($id) : bool
    {
        $jwtToken = $this->tokenStorage->getToken()->getAttribute("JWTToken");
        $response = $this->client->request('DELETE', 'http://127.0.0.1/api/offres/'.$id,[
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