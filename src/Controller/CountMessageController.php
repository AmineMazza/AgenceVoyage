<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Service\MessageApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CountMessageController extends AbstractController
{
    #[Route('/countmessage', name: 'app_count_message')]
    public function index(MessageRepository $messageRepository): JsonResponse
    {
        $message = $messageRepository->CountMessages();

        return new JsonResponse([
            'message' => $message[0],
        ]);
    }

    #[Route('/seeMessage/{idM}', name: 'app_count_message')]
    public function seemssage(MessageApiService $messageApiService, array $_route_params)
    {
        $bool = $messageApiService->SeenMessage($_route_params['idM']);

        return $bool;
    }
}
