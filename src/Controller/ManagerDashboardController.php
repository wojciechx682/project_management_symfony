<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ManagerDashboardController extends AbstractController
{
    //#[Route('/manager/dashboard', name: 'app_manager_dashboard')]
    #[Route('/manager', name: 'app_manager_dashboard')]
    public function index(): Response
    {
        return $this->render('manager_dashboard/index.html.twig', [
            'controller_name' => 'ManagerDashboardController',
        ]);
    }
}
