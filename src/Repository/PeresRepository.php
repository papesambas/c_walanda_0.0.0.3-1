<?php

namespace App\Repository;

use App\Entity\Peres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Peres>
 */
class PeresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Peres::class);
    }

    /**
     * Summary of save
     * @param \App\Entity\Peres $entity
     * @param bool $flush
     * @return void
     */
    public function save(Peres $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Summary of remove
     * @param \App\Entity\Peres $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Peres $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

            public function findForAll(): array
        {
            return $this->createQueryBuilder('p')
                ->select('p','n','pre', 'pr', 'te','te2')
                ->leftJoin('p.nom', 'n')
                ->leftJoin('p.prenom', 'pre')
                ->leftJoin('p.profession', 'pr')
                ->leftJoin('p.telephone1', 'te')
                ->leftJoin('p.telephone2', 'te2')
                ->where('p.nom IS NOT NULL')
                ->andWhere('p.prenom IS NOT NULL')
                ->orderBy('n.designation', 'ASC')
                ->addOrderBy('pre.designation', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }


        public function findTop10(): array
        {
            return $this->createQueryBuilder('p')
                ->select('p', 'pr', 'te')
                ->innerJoin('p.profession', 'pr')
                ->innerJoin('p.telephone1', 'te')
                ->where('p.nom IS NOT NULL')
                ->andWhere('p.prenom IS NOT NULL')
                ->orderBy('p.nom', 'ASC')
                ->addOrderBy('p.prenom', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;
        }

    //    /**
    //     * @return Peres[] Returns an array of Peres objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Peres
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
