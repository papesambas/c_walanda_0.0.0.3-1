<?php

namespace App\Repository;

use App\Entity\Telephones1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Telephones1>
 */
class Telephones1Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Telephones1::class);
    }

    /**
     * Summary of findOneByNumero
     * @param mixed $label
     */
    public function findOneByNumero($label): ?Telephones1
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.numero = :val')
            ->setParameter('val', $label)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    //    /**
    //     * @return Telephones1[] Returns an array of Telephones1 objects
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

    //    public function findOneBySomeField($value): ?Telephones1
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
