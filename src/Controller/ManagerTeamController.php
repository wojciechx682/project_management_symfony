<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ManagerTeamController extends AbstractController
{
    #[Route('/manager/teams', name: 'app_manager_teams')]
    public function index(): Response
    {
        // lista wszystkich zespołów
        echo "Manager Teams";
        exit();
    }
}
