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

#[Route('/dashboard/agent')]
class AgentController extends AbstractController
{
    #[Route('/', name: 'app_agent_index', methods: ['GET'])]
    public function index(AgentApiService $agentApiService): Response
    {
        return $this->render('agent/index.html.twig', [
            'agents' => $agentApiService->getAgents(),
        ]);
    }

    #[Route('/new', name: 'app_agent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserApiService $userApiService, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $agent = new Agent();
        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $extraFields = $form->getExtraData();
            $user = new User();
            $user->setEmail($extraFields['email']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $extraFields['password']
                )
            );
            if($agent->isBstatus()) $user->setRoles(['ROLE_AGENT']);
            else $user->setRoles(['ROLE_USER']);
            $user->setAgent($agent);

            $userApiService->AddUser($user);

            return $this->redirectToRoute('app_agent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agent/new.html.twig', [
            'agent' => $agent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agent_show', methods: ['GET'])]
    public function show(Agent $agent): Response
    {
        return $this->render('agent/show.html.twig', [
            'agent' => $agent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_agent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agent $agent, AgentApiService $agentApiService, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logo = $form->get('logo')->getData();
            if ($logo){
                $originalFilename = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logo->guessExtension();
                
                
                $agent->setLogo('/assets/agentLogo/'.$newFilename);
                
            }
            $status = $agentApiService->UpdateAgent($agent);
            if($status){
                if($logo){
                    try {
                        $logo->move(
                            $this->getParameter('logo_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        throw new Exception($e);
                    }
                }
            }

            return $this->redirectToRoute('app_agent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('agent/edit.html.twig', [
            'agent' => $agent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agent_delete', methods: ['POST'])]
    public function delete(Request $request, Agent $agent, AgentApiService $agentApiService, UserApiService $userApiService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agent->getId(), $request->request->get('_token'))) {
            $bool = $agentApiService->DeleteAgent($agent);
            if($bool){
                $userApiService->DeleteUser($agent->getIdUser()->getId());
            }
        }

        return $this->redirectToRoute('app_agent_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/valid', name: 'app_agent_valid', methods: ['GET'])]
    public function valid(Agent $agent, UserApiService $userApiService): Response
    {
        $userApiService->ValidAgent($agent->getIdUser()->getId(),$agent->getId());

        return $this->redirectToRoute('app_agent_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/unvalid', name: 'app_agent_unvalid', methods: ['GET'])]
    public function unvalid(Agent $agent, UserApiService $userApiService): Response
    {
        $userApiService->UnvalidAgent($agent->getIdUser()->getId(),$agent->getId());

        return $this->redirectToRoute('app_agent_index', [], Response::HTTP_SEE_OTHER);
    }
}
