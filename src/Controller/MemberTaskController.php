<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MemberTaskController extends AbstractController
{
    #[Route('/member/tasks', name: 'app_member_tasks')]
    public function index(): Response
    {
        // lista wszystkich zadań
        echo "Member Tasks";
        exit();
    }
}
