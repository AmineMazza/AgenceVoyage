<?php

namespace App\Controller\Admin;

use App\Entity\Agent;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/Dashboard', name: 'Dashboard')]
    public function index(): Response
    {
      
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Norsysproject');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('users', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('agent', 'fas fa-list', Agent::class);
    }
}
