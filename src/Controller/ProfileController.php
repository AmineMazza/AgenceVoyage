<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\User;
use App\Form\AgentType;
use App\Service\AgentApiService;
use App\Service\UserApiService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('profile/agent')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile_index', methods: ['GET'])]
    public function index(AgentApiService $agentApiService): Response
    {
        return $this->render('profile/index.html.twig', [
            'agent' => $agentApiService->getAgents(),
        ]);
    }
}
