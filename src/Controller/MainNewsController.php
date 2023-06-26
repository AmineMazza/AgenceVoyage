<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainNewsController extends AbstractController
{
    #[Route('/main/news', name: 'app_main_news')]
    public function index(): Response
    {
        return $this->render('main_news/index.html.twig', [
            'controller_name' => 'MainNewsController',
        ]);
    }
}
