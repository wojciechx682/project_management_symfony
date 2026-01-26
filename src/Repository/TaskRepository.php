<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    // Lista wszystkich tasków PM'a
    public function findForLeader(User $leader): array
    {
        return $this->createQueryBuilder('task')
            ->innerJoin('task.project', 'p')
            ->innerJoin('p.team', 't')
            ->andWhere('t.leader = :leader')
            ->setParameter('leader', $leader)
            ->orderBy('task.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Lista tasków po kliknięciu w projekt
    public function findOneForLeader(int $id, User $leader): ?Task
    {
        return $this->createQueryBuilder('task')
            ->innerJoin('task.project', 'p')
            ->innerJoin('p.team', 't')
            ->andWhere('task.id = :id')
            ->andWhere('t.leader = :leader')
            ->setParameter('id', $id)
            ->setParameter('leader', $leader)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // taski PM'a w ramach konkretnego projektu
    public function findForLeaderAndProject(User $leader, int $projectId): array
    {
        return $this->createQueryBuilder('task')
            ->innerJoin('task.project', 'p')
            ->innerJoin('p.team', 't')
            ->andWhere('p.id = :projectId')
            ->andWhere('t.leader = :leader')
            ->setParameter('projectId', $projectId)
            ->setParameter('leader', $leader)
            ->orderBy('task.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // pojedynczy task PM'a w ramach projektu
    public function findOneForLeaderAndProject(User $leader, int $taskId, int $projectId): ?Task
    {
        return $this->createQueryBuilder('task')
            ->innerJoin('task.project', 'p')
            ->innerJoin('p.team', 't')
            ->andWhere('task.id = :taskId')
            ->andWhere('p.id = :projectId')
            ->andWhere('t.leader = :leader')
            ->setParameter('taskId', $taskId)
            ->setParameter('projectId', $projectId)
            ->setParameter('leader', $leader)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Task[] Returns an array of Task objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Task
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
