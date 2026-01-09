<?php

namespace App\Security;

use Symfony\Bundle\SecurityBundle\Security;

final class DashboardResolver
{
    public function __construct(
        private Security $security,
    ) {}

    /**
     * Returns the dashboard route name for the current user
     * If user is not logged in -> null
     */
    public function getDashboardRouteNameForCurrentUser(): ?string
    {
        if (!$this->security->getUser()) {
            return null;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return 'app_admin_dashboard';
        }

        if ($this->security->isGranted('ROLE_PM')) {
            return 'app_manager_dashboard';
        }

        return 'app_member_dashboard';
    }
}
