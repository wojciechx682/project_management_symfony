<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\ManagerTaskType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/manager/project/{projectId}/task')]
final class ManagerProjectTaskController extends AbstractController
{


    #[Route('/new', name: 'manager_project_task_new', methods: ['GET','POST'])]
    public function new(
        int $projectId,
        Request $request,
        ProjectRepository $projectRepository,
        EntityManagerInterface $manager
    ): Response
    {
        $user = $this->getUser();
        if (!$user) throw $this->createAccessDeniedException();

        $project = $projectRepository->findOneForLeader($projectId, $user);
        if (!$project) throw $this->createNotFoundException();

        $task = new Task();
        $task->setProject($project); // projekt z URL, nie z formularza

        $form = $this->createForm(ManagerTaskType::class, $task, [
            'manager' => $user,
            'project' => $project, // opcjonalnie: żeby filtrować assignedUser do teamu
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($task);
            $manager->flush();

            $this->addFlash('success', 'Task created.');
            return $this->redirectToRoute('manager_project_show', ['id' => $projectId]);
        }

        return $this->render('manager/task/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{taskId}', name: 'manager_project_task_show', methods: ['GET'])]
    public function show(
        int $projectId,
        int $taskId,
        ProjectRepository $projectRepository,
        TaskRepository $taskRepository
    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // Sprawdź, czy projekt należy do tego PM'a
        $project = $projectRepository->findOneForLeader($projectId, $user);
        if (!$project) {
            throw $this->createNotFoundException();
        }

        // Pobierz taska tylko jeśli jest w tym projekcie i należy do PM
        $task = $taskRepository->findOneForLeaderAndProject($user, $taskId, $projectId);
        if (!$task) {
            throw $this->createNotFoundException();
        }

        return $this->render('manager/task/show.html.twig', [
            'project' => $project,
            'task' => $task,
        ]);
    }
}
