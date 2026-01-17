<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Project;
use App\Entity\Task;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\User;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {

    }

    public function load(ObjectManager $manager): void
    {
        /*// $product = new Product();
        // $manager->persist($product);
        $roles = ['ROLE_ADMIN', 'ROLE_PM', 'ROLE_MEMBER']; // v.1
        $faker = Factory::create('en_US');
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail($faker->unique()->safeEmail());
            $user->setRole($roles[$i]);
            // (!) ONLY TEMPORARY - safer method above - need to modify User Entity Class
            //$user->setPassword(
            //    password_hash('12345', PASSWORD_DEFAULT)
            //);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, '12345')
            );
            //$user->setCreatedAt(
            //    \DateTimeImmutable::createFromMutable(
            //        $faker->dateTimeBetween('-1 year', 'now')
            //    )
            //);
            $user->setCreatedAt(new \DateTimeImmutable());
            //$user->setIsApproved($faker->boolean(80)); // 80% true, 20% false
            $user->setIsApproved(true);
            $manager->persist($user);
        }
        $manager->flush();    */

        $users = [];
        $roles = ['ROLE_ADMIN', 'ROLE_PM', 'ROLE_MEMBER']; // v.1
        $faker = Factory::create('en_US');

        for ($i = 0; $i < 3; $i++) {
            $user = new User();

            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail($faker->unique()->safeEmail());
            $user->setRole($roles[$i]);

            $user->setPassword(
                $this->passwordHasher->hashPassword($user, '12345')
            );

            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setIsApproved(true);

            $manager->persist($user);
            $users[] = $user;
        }

        $teams = [];

        for ($i = 0; $i < 1; $i++) {
            $team = new Team();
            //$team->setName('Team ' . ($i + 1));
            $team->setName('PM App Team');
            $team->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($team);
            $teams[] = $team;
        }

        $teams[0]->addUser($users[0]); // Admin
        $teams[0]->addUser($users[1]); // PM
        $teams[0]->addUser($users[2]); // Member

        $project = new Project();

        $project->setName('Project Management App');
        $project->setDescription('A web-based application for project management, team collaboration, and task tracking');
        $project->setStartDate(new \DateTimeImmutable());
        $project->setEndDate(new \DateTimeImmutable());
        $project->setStatus('In Progress'); // lub 'new', zależnie od konwencji
        $project->setCreatedAt(new \DateTimeImmutable());

        $project->setTeam($teams[0]); // PM App Team

        $manager->persist($project);

        $tasksData = [
            [
                'title' => 'Design database schema',
                'description' => 'Create initial ER diagram and define relations between entities.',
                'priority' => 'High',
                'status' => 'To Do',
                'due_date' => '+7 days',
            ],
            [
                'title' => 'Implement authentication system',
                'description' => 'Configure Symfony Security, login, roles and access control.',
                'priority' => 'High',
                'status' => 'In Progress',
                'due_date' => '+10 days',
            ],
            [
                'title' => 'Create project dashboard UI',
                'description' => 'Build Twig templates for dashboard and role-based navigation.',
                'priority' => 'Medium',
                'status' => 'To Do',
                'due_date' => '+14 days',
            ],
        ];

        $tasks = [];

        foreach ($tasksData as $data) {
            $task = new Task();

            $task->setTitle($data['title']);
            $task->setDescription($data['description']);
            $task->setPriority($data['priority']);
            $task->setStatus($data['status']);
            $task->setDueDate(new \DateTimeImmutable($data['due_date']));
            $task->setCreatedAt(new \DateTimeImmutable());

            $task->setProject($project);
            $task->setAssignedUser($users[2]); // Member

            $manager->persist($task);
            $tasks[] = $task; //
        }

        foreach ($tasks as $task) {
            for ($i = 0; $i < 3; $i++) {
                $comment = new Comment();

                $comment->setContent(
                    $faker->sentence(rand(8, 14))
                );
                $comment->setCreatedAt(new \DateTimeImmutable());

                // relacje
                $comment->setTask($task);

                // autor komentarza (rotacja: Admin / PM / Member)
                //$comment->setUser($users[$i % count($users)]);
                $comment->setUser($users[2]);

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
