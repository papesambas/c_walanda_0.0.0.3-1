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

    public function save(Communes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Communes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // src/Repository/CommunesRepository.php
public function search(string $query, ?int $cercleId = null): array
{
    $qb = $this->createQueryBuilder('c')
        ->select('c.id', 'c.designation AS text')
        ->where('c.designation LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->orderBy('c.designation', 'ASC')
        ->setMaxResults(10);

    if ($cercleId) {
        $qb->andWhere('c.cercle = :cercleId')
           ->setParameter('cercleId', $cercleId);
    }

    return $qb->getQuery()->getArrayResult();
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
