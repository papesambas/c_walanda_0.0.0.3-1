<?php

namespace App\Repository;

use App\Entity\Communes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Communes>
 */
class CommunesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Communes::class);
    }

    public function findByCercle(int $cercleId): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.cercle = :cercleId')
            ->setParameter('cercleId', $cercleId)
            ->orderBy('c.designation', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCercleAndDesignation(int $cercleId, ?string $term = null): array
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.cercle = :cercleId')
            ->setParameter('cercleId', $cercleId);

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
    //     * @return Communes[] Returns an array of Communes objects
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

    //    public function findOneBySomeField($value): ?Communes
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
