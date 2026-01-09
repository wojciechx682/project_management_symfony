<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use App\Security\DashboardResolver;

final class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private DashboardResolver $dashboardResolver
    ) {

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        // (POST) - This method will be executed when: 
            // - User provided correct login data (credentials),
            // - Password was Correct
            // - Symfony created a token and considered the login as a success

        // v.1
        /*$roles = $token->getRoleNames(); // role from getRoles() of our User (array)
        if (in_array('ROLE_ADMIN', $roles, true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin_dashboard'));
        }
        if (in_array('ROLE_PM', $roles, true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_manager_dashboard'));
        }
        return new RedirectResponse($this->urlGenerator->generate('app_member_dashboard'));*/

        $route = $this->dashboardResolver->getDashboardRouteNameForCurrentUser() ?? 'app_member_dashboard';

        return new RedirectResponse($this->urlGenerator->generate($route));
    }
}
