<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\ProjectRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/team')]
final class AdminTeamController extends AbstractController
{
    #[Route(name: 'app_admin_team_index', methods: ['GET'])]
    public function index(TeamRepository $teamRepository): Response
    {
        return $this->render('admin/team/index.html.twig', [
            'teams' => $teamRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $team->addUser($team->getLeader()); // leader też jest członkiem

            $entityManager->persist($team);
            $entityManager->flush();

            $this->addFlash('success', 'Team created successfully');

            return $this->redirectToRoute('app_admin_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/team/new.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_team_show', methods: ['GET'])]
    public function show(Team $team): Response
    {
        return $this->render('admin/team/show.html.twig', [
            'team' => $team,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $team->addUser($team->getLeader()); // gdy zmienisz lidera, dopnij go do members

            $entityManager->flush();

            $this->addFlash('success', 'Team updated successfully');

            return $this->redirectToRoute('app_admin_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/team/edit.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_team_delete', methods: ['POST'])]
    public function delete(Request $request, Team $team, EntityManagerInterface $entityManager, ProjectRepository $projectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->getPayload()->getString('_token'))) {

            $count = $projectRepository->count(['team' => $team]);
            if ($count > 0) {
                $this->addFlash('error', 'You cannot delete a team that has projects assigned to it.');
                return $this->redirectToRoute('app_admin_team_index');
            }

            $entityManager->remove($team);
            $entityManager->flush();

            $this->addFlash('success', 'Team deleted successfully');
        }

        return $this->redirectToRoute('app_admin_team_index', [], Response::HTTP_SEE_OTHER);
    }
}
