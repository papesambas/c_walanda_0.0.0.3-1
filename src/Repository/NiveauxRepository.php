<?php

namespace App\Repository;

use App\Entity\Classes;
use App\Entity\Niveaux;
use App\Entity\Etablissements;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Niveaux>
 */
class NiveauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Niveaux::class);
    }

    public function findByEtablissement(Etablissements $etablissement): array
    {
        return $this->createQueryBuilder('n')
            ->select('DISTINCT n') // Évite les doublons
            ->join('n.cycle', 'c') // Jointure avec le cycle
            ->join('c.enseignement', 'ens') // Jointure avec les enseignements du cycle
            ->join('ens.etablissements', 'e') // Jointure avec les établissements de l'enseignement
            ->where('e.id = :etablissementId')
            ->setParameter('etablissementId', $etablissement->getId())
            ->orderBy('n.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByClasse(?Classes $classe): ?Niveaux
{
        $qb = $this->createQueryBuilder('n');
            

        if ($classe) {
            $qb->where('n.classe = :claase')
            ->setParameter('classe', $classe);
        }

        return $qb->orderBy('n.designation', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();

}

    public function findByCycleAndDesignation(int $cycleId, ?string $term = null): array
    {
        $qb = $this->createQueryBuilder('n')
            ->where('n.cycle = :cycleId')
            ->setParameter('cycleId', $cycleId);

        if ($term) {
            $qb->andWhere('n.designation LIKE :term')
                ->setParameter('term', '%' . $term . '%');
        }

        return $qb->orderBy('n.designation', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Niveaux[] Returns an array of Niveaux objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Niveaux
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
