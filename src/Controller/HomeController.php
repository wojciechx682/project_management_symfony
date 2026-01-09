<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Security\DashboardResolver;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(DashboardResolver $dashboardResolver): RedirectResponse
    {
        // v.0
        /*if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard'); // / -> logged in -> /dashboard
        }*/

        // v1
        /*if ($this->getUser()) { // null if NOT logged in | User object if Logged in
            // (!) User IS logged in -> redirect based on role
            if ($this->isGranted('ROLE_ADMIN')) { // asks, does the current user have this permission?
                return $this->redirectToRoute('app_admin_dashboard');   // /admin
            }
            if ($this->isGranted('ROLE_PM')) {
                return $this->redirectToRoute('app_manager_dashboard'); // /manager
            }
            return $this->redirectToRoute('app_member_dashboard');      // /member
        }
        return $this->redirectToRoute('app_login'); // / -> not logged in -> /login*/

        $route = $dashboardResolver->getDashboardRouteNameForCurrentUser();

        if ($route !== null) {
            return $this->redirectToRoute($route);
        }

        return $this->redirectToRoute('app_login');
    }
}