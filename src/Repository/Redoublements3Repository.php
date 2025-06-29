<?php

namespace App\Repository;

use App\Entity\Redoublements3;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Redoublements3>
 */
class Redoublements3Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Redoublements3::class);
    }

    public function findByNiveauAndScolarite1AndScolarite2AndRedoublement2(int $niveauId, int $scolarite1Id, int $scolarite2Id, int $redoublement2Id): array
    {
        $qb = $this->createQueryBuilder('r3')
            ->where('r3.niveau = :niveauId')
            ->andWhere('r3.scolarite1 = :scolarite1Id')
            ->andWhere('r3.scolarite2 = :scolarite2Id')
            ->andWhere('r3.redoublement2 = :redoublement2Id')
            ->setParameter('niveauId', $niveauId)
            ->setParameter('scolarite1Id', $scolarite1Id)
            ->setParameter('scolarite2Id', $scolarite2Id)
            ->setParameter('redoublement2Id', $redoublement2Id);


        return $qb->orderBy('r3.designation', 'ASC')
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Redoublements3[] Returns an array of Redoublements3 objects
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

    //    public function findOneBySomeField($value): ?Redoublements3
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
