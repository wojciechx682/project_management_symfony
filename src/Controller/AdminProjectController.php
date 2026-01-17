<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminProjectController extends AbstractController
{
    #[Route('/admin/projects', name: 'app_admin_projects')]
    public function index(): Response
    {
        // lista wszystkich projektów
        echo "Admin Projects";
        exit();
    }

    /*#[Route('/admin/projects/{id}', name: 'admin_projects_show')]
    public function show(int $id): Response
    {
        // szczegóły projektu
    }

    #[Route('/admin/projects/new', name: 'admin_projects_new')]
    public function new(Request $request): Response
    {
        // formularz + create
    }

    #[Route('/admin/projects/{id}/edit', name: 'admin_projects_edit')]
    public function edit(int $id, Request $request): Response
    {
        // edycja projektu
    }

    #[Route('/admin/projects/{id}/delete', name: 'admin_projects_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        // usuwanie
    }*/
}
