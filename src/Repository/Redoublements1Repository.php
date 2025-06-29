<?php

namespace App\Repository;

use App\Entity\Redoublements1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Redoublements1>
 */
class Redoublements1Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Redoublements1::class);
    }

    public function findByNiveauAndScolarite1AndScolarite2(int $niveauId, int $scolarite1Id, int $scolarite2Id): array
    {
        $qb = $this->createQueryBuilder('r1')
            ->where('r1.niveau = :niveauId')
            ->andWhere('r1.scolarite1 = :scolarite1Id')
            ->andWhere('r1.scolarite2 = :scolarite2Id')
            ->setParameter('niveauId', $niveauId)
            ->setParameter('scolarite1Id', $scolarite1Id)
            ->setParameter('scolarite2Id', $scolarite2Id);


        return $qb->orderBy('r1.designation', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Redoublements1[] Returns an array of Redoublements1 objects
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

    //    public function findOneBySomeField($value): ?Redoublements1
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
