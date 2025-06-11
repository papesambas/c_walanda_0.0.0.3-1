<?php

namespace App\Repository;

use App\Entity\Niveaux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Niveaux>
 */
class NiveauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Niveaux::class);
    }

    public function findByCycleAndDesignation(int $cycleId, ?string $term = null): array
{
    $qb = $this->createQueryBuilder('n')
        ->where('n.cycle = :cycleId')
        ->setParameter('cycleId', $cycleId);

    if ($term) {
        $qb->andWhere('n.designation LIKE :term')
            ->setParameter('term', '%' . $term . '%');
    }

    return $qb->orderBy('n.designation', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
}

    //    /**
    //     * @return Niveaux[] Returns an array of Niveaux objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Niveaux
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
