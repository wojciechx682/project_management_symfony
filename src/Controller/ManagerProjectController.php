<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ManagerProjectController extends AbstractController
{
    #[Route('/manager/projects', name: 'app_manager_projects')]
    public function index(): Response
    {
        // lista wszystkich projektów
        echo "Manager Projects";
        exit();
    }
}
