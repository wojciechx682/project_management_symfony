<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminUserController extends AbstractController
{
    #[Route('/admin/users', name: 'app_admin_users')]
    public function index(): Response
    {
        // lista użytkowników
        echo "Admin Users";
        exit();
    }

    /*#[Route('/admin/users/{id}', name: 'admin_users_show')]
    public function show(int $id): Response
    {
        // szczegóły usera
    }

    #[Route('/admin/users/{id}/approve', name: 'admin_users_approve', methods: ['POST'])]
    public function approve(int $id): Response
    {
        // akceptacja konta
    }

    #[Route('/admin/users/{id}/block', name: 'admin_users_block', methods: ['POST'])]
    public function block(int $id): Response
    {
        // blokada konta
    }*/
}
