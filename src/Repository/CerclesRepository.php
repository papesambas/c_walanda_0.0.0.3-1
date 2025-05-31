<?php

namespace App\Repository;

use App\Entity\Cercles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cercles>
 */
class CerclesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cercles::class);
    }

    public function save(Cercles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cercles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search(string $query, ?int $regionId = null): array
{
    $qb = $this->createQueryBuilder('c')
        ->select('c.id', 'c.designation AS text')
        ->where('c.designation LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->orderBy('c.designation', 'ASC')
        ->setMaxResults(10);

    if ($regionId) {
        $qb->andWhere('c.region = :regionId')
           ->setParameter('regionId', $regionId);
    }

    return $qb->getQuery()->getArrayResult();
}

    //    /**
    //     * @return Cercles[] Returns an array of Cercles objects
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

    //    public function findOneBySomeField($value): ?Cercles
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
