<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Service\MessageApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class MessageController extends AbstractController
{
    #[Route('dashboard/message/', name: 'app_message_index', methods: ['GET'])]
    public function index(MessageApiService $MessageApiService): Response
    {
        
        return $this->render('message/index.html.twig', [
            'messages' => $MessageApiService->getMessages(),
        ]);
    }

    #[Route('/contact', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MessageApiService $MessageApiService): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $MessageApiService->AddMessage($message);

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('offre/{idO}/message', name: 'app_message_plusinfo', methods: ['GET', 'POST'])]
    public function plusinfo(Request $request, MessageApiService $MessageApiService, array $_route_params): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $MessageApiService->AddMessage($message, $_route_params['idO']);

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('dashboard/message/{id}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('dashboard/message/{id}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, MessageApiService $MessageApiService): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $MessageApiService->UpdateMessage($message);

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('dashboard/message/{id}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, MessageApiService $MessageApiService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $MessageApiService->DeleteMessage($message->getId());
        }

        return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
    }
}
