<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Security\DashboardResolver;

final class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, DashboardResolver $dashboardResolver): Response
    {
        // v.0
        /*if ($this->getUser()) { 
            return $this->redirectToRoute('app_dashboard'); // /login -> logged in -> /dashboard
        }*/

        // v.1 - If User is logged in, dont show /login page -> redirect to right dashboard
        /*if ($this->getUser()) {
            return $this->redirectAfterLoginByRole();
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);*/

        $route = $dashboardResolver->getDashboardRouteNameForCurrentUser();

        if ($route !== null) {
            return $this->redirectToRoute($route);
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    // v.1 - Redirect AFTER user is logged in, and tries to manually enter the /login page
    /*private function redirectAfterLoginByRole(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_dashboard');   // /admin
        }

        if ($this->isGranted('ROLE_PM')) {
            return $this->redirectToRoute('app_manager_dashboard'); // /manager
        }

        return $this->redirectToRoute('app_member_dashboard');      // /member
    }*/

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
