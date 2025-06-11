<?php

namespace App\Repository;

use App\Entity\Scolarites1;
use App\Entity\Scolarites2;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Scolarites2>
 */
class Scolarites2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scolarites2::class);
    }

    public function findByNiveauAndScolarite(int $niveauId, int $scolarite1Id, ?string $term = null,): array
    {
        $qb = $this->createQueryBuilder('s2')
            ->where('s2.niveau = :niveauId')
            ->where('s2.scolarite1 = :scolarite1Id')
            ->setParameter('niveauId', $niveauId)
            ->setParameter('scolarite1Id', $scolarite1Id);;

        if ($term) {
            $qb->andWhere('s1.scolarite LIKE :term')
                ->setParameter('term', '%' . $term . '%');
        }

        return $qb->orderBy('s1.scolarite', 'ASC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Scolarites2[] Returns an array of Scolarites2 objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Scolarites2
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
