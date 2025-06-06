<?php

namespace App\Repository;

use App\Entity\Cercles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cercles>
 */
class CerclesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cercles::class);
    }

    // CerclesRepository.php
public function findByRegionAndDesignation(int $regionId, ?string $term = null): array
{
    $qb = $this->createQueryBuilder('c')
        ->where('c.region = :regionId')
        ->setParameter('regionId', $regionId);

    if ($term) {
        $qb->andWhere('c.designation LIKE :term')
            ->setParameter('term', '%' . $term . '%');
    }

    return $qb->orderBy('c.designation', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
}

    //    /**
    //     * @return Cercles[] Returns an array of Cercles objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Cercles
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
