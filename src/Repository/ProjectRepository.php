<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    // Projekty Lidera -->

    public function findForLeader(User $leader): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.team', 't')
            ->andWhere('t.leader = :leader')
            ->setParameter('leader', $leader)
            ->orderBy('p.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOneForLeader(int $id, User $leader): ?Project
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.team', 't')
            ->andWhere('p.id = :id')
            ->andWhere('t.leader = :leader')
            ->setParameter('id', $id)
            ->setParameter('leader', $leader)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Project[] Returns an array of Project objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Project
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
