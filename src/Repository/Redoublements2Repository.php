<?php

namespace App\Repository;

use App\Entity\Redoublements2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Redoublements2>
 */
class Redoublements2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Redoublements2::class);
    }

        public function findByNiveauAndScolarite1AndScolarite2AndRedoublement1(int $niveauId, int $scolarite1Id, int $scolarite2Id, int $redoublement1Id): array
    {
        $qb = $this->createQueryBuilder('r2')
            ->where('r2.niveau = :niveauId')
            ->andWhere('r2.scolarite1 = :scolarite1Id')
            ->andWhere('r2.scolarite2 = :scolarite2Id')
            ->andWhere('r2.redoublement1 = :redoublement1Id')
            ->setParameter('niveauId', $niveauId)
            ->setParameter('scolarite1Id', $scolarite1Id)
            ->setParameter('scolarite2Id', $scolarite2Id)
            ->setParameter('redoublement1Id', $redoublement1Id);


        return $qb->orderBy('r2.designation', 'ASC')
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Redoublements2[] Returns an array of Redoublements2 objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Redoublements2
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
