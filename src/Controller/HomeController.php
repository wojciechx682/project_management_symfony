<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): RedirectResponse
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard'); // / -> logged in -> /dashboard
        }

        return $this->redirectToRoute('app_login'); // / -> not logged in -> /login
    }
}