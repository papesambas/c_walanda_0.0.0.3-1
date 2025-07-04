<?php

namespace App\Repository;

use App\Entity\Cycles;
use App\Entity\Enseignements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cycles>
 */
class CyclesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cycles::class);
    }

    public function findForAll(?Enseignements $enseignements): array
    {
        $qb = $this->createQueryBuilder('c');

        if ($enseignements) {
            $qb->andWhere(':enseignements MEMBER OF c.enseignement')
                ->setParameter('enseignements', $enseignements);
        }

        return $qb->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    // CyclesRepository.php
public function findByEnseignementOrdered(Enseignements $enseignement): array
{
    return $this->createQueryBuilder('c')
        ->innerJoin('c.enseignement', 'e')
        ->where('e = :enseignement')
        ->setParameter('enseignement', $enseignement)
        ->orderBy('c.designation', 'ASC')
        ->getQuery()
        ->getResult();
}
    public function findByEnseignementAndDesignation(int $enseignementId, ?string $term = null): array
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.enseignement', 'e')                    // jointure
            ->where('e.id = :enseignementId')               // on filtre sur l’ID de e
            ->setParameter('enseignementId', $enseignementId);

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
    //     * @return Cycles[] Returns an array of Cycles objects
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

    //    public function findOneBySomeField($value): ?Cycles
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
