<?php

namespace App\Repository;

use App\Entity\LieuNaissances;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LieuNaissances>
 */
class LieuNaissancesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LieuNaissances::class);
    }

    public function findByCommune(int $communeId): array
    {
        return $this->createQueryBuilder('l')
            ->where('l.commune = :communeId')
            ->setParameter('communeId', $communeId)
            ->orderBy('l.designation', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCommuneAndDesignation(int $communeId, ?string $term = null): array
    {
        $qb = $this->createQueryBuilder('l')
            ->where('l.commune = :communeId')
            ->setParameter('communeId', $communeId);

        if ($term) {
            $qb->andWhere('l.designation LIKE :term')
                ->setParameter('term', '%' . $term . '%');
        }

        return $qb->orderBy('l.designation', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return LieuNaissances[] Returns an array of LieuNaissances objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LieuNaissances
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
