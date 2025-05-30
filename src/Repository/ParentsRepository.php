<?php

namespace App\Repository;

use App\Entity\Parents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Parents>
 */
class ParentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parents::class);
    }

    public function save(Parents $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function remove(Parents $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findForAll(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'pe', 'me','n', 'pre', 'pro', 'te')
            ->leftJoin('p.pere', 'pe')
            ->addSelect('pe')
            ->leftJoin('pe.nom', 'n')
            ->leftJoin('pe.prenom', 'pre')
            ->leftJoin('pe.profession', 'pro')
            ->leftJoin('pe.telephone1', 'te')
            ->addSelect('n', 'pre', 'pro', 'te')
            ->leftJoin('p.mere', 'me')
            ->addSelect('me')
            ->leftJoin('me.nom', 'n1')
            ->leftJoin('me.prenom', 'pre1')
            ->leftJoin('me.profession', 'pro1')
            ->leftJoin('me.telephone1', 'te1')
            ->addSelect('n1', 'pre1', 'pro1', 'te1')
            ->where('p.pere IS NOT NULL')
            ->andWhere('p.mere IS NOT NULL')
            ->orderBy('n.designation', 'ASC')
            ->addOrderBy('pre.designation', 'ASC')
            ->addOrderBy('n1.designation', 'ASC')
            ->addOrderBy('pre1.designation', 'ASC')
            ->getQuery()
            ->getResult();
        ;
    }



    //    /**
    //     * @return Parents[] Returns an array of Parents objects
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

    //    public function findOneBySomeField($value): ?Parents
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
