<?php

namespace App\Repository;

use App\Entity\Meres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meres>
 */
class MeresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meres::class);
    }

    public function findForAll(): array
    {
        return $this->createQueryBuilder('m')
            ->select('m','n','pre', 'pr', 'te', 'te2')
            ->leftJoin('m.nom', 'n')
            ->leftJoin('m.prenom', 'pre')
            ->leftJoin('m.profession', 'pr')
            ->leftJoin('m.telephone1', 'te')
            ->leftJoin('m.telephone2', 'te2')
            ->where('m.nom IS NOT NULL')
            ->andWhere('m.prenom IS NOT NULL')
            ->orderBy('n.designation', 'ASC')
            ->addOrderBy('pre.designation', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    //    /**
    //     * @return Meres[] Returns an array of Meres objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Meres
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
