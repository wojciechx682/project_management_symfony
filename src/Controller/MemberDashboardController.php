<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MemberDashboardController extends AbstractController
{
    //#[Route('/member/dashboard', name: 'app_member_dashboard')]
    #[Route('/member', name: 'app_member_dashboard')]
    public function index(): Response
    {
        return $this->render('member/dashboard/index.html.twig', [
            'controller_name' => 'MemberDashboardController',
        ]);
    }
}
