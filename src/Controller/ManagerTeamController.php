<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/manager/team')]
final class ManagerTeamController extends AbstractController
{
    #[Route(name: 'manager_team_controller', methods: ['GET'])]
    public function index(TeamRepository $teamRepository): Response
    {
        /*return $this->render('manager/team/index.html.twig', [
            'teams' => $teamRepository->findAll(),
        ]);*/

        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('manager/team/index.html.twig', [
            'teams' => $teamRepository->findForLeader($user),
        ]);
    }

    #[Route('/{id}', name: 'manager_team_controller_show', methods: ['GET'])]
    public function show(int $id, TeamRepository $teamRepository): Response
    {
        /*return $this->render('manager/team/show.html.twig', [
            'team' => $team,
        ]);*/

        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $team = $teamRepository->findOneForLeader($id, $user);
        if (!$team) {
            throw $this->createNotFoundException(); // 404
        }

        return $this->render('manager/team/show.html.twig', [
            'team' => $team,
        ]);
    }
}
