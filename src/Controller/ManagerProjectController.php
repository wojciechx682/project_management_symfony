<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\Project1Type;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/manager/project')]
final class ManagerProjectController extends AbstractController
{
    #[Route(name: 'manager_projects_index')]
    public function index(ProjectRepository $projectRepository): Response
    {
        // lista wszystkich projektów
        //echo "Manager Projects";
        //exit();

        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }
        // Wersja A:
        $projects = $projectRepository->findForLeader($user);

        return $this->render('manager/project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/new', name: 'manager_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(Project1Type::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            $this->addFlash('success', 'Project created successfully');

            return $this->redirectToRoute('manager_projects_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('manager/project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'manager_project_show', methods: ['GET'])]
    public function show(int $id, ProjectRepository $projectRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $project = $projectRepository->findOneForLeader($id, $user);

        if (!$project) {
            throw $this->createNotFoundException(); // 404
        }

        return $this->render('manager/project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'manager_project_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, ProjectRepository $projectRepository, EntityManagerInterface $entityManager): Response
    {
        /*$form = $this->createForm(Project1Type::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('manager_projects_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('manager/project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);*/

        // Error above - PM can change projects that doesnt belong to him.

        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $project = $projectRepository->findOneForLeader($id, $user);

        if (!$project) {
            throw $this->createNotFoundException(); // 404
        }

        $form = $this->createForm(Project1Type::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Project updated successfully');

            return $this->redirectToRoute('manager_projects_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('manager/project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'manager_project_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, ProjectRepository $projectRepository, EntityManagerInterface $entityManager): Response
    {
        /*if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('manager_projects_index', [], Response::HTTP_SEE_OTHER);*/

        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $project = $projectRepository->findOneForLeader($id, $user);
        if (!$project) {
            throw $this->createNotFoundException(); // 404
        }

        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
            $this->addFlash('success', 'Project deleted successfully');
        }

        return $this->redirectToRoute('manager_projects_index', [], Response::HTTP_SEE_OTHER);
    }
}
