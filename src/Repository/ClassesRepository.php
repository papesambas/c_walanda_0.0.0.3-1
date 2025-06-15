<?php

namespace App\Repository;

use App\Entity\Classes;
use App\Entity\Eleves;
use App\Entity\Etablissements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Classes>
 */
class ClassesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classes::class);
    }

    public function findByNiveauAndDesignation(int $niveauId, ?string $term = null, ?Etablissements $etablissement): array
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.niveau = :niveauId')
            ->setParameter('niveauId', $niveauId);

        if ($etablissement) {
            $qb->andWhere('c.etablissement = :etablissement')
                ->setParameter('etablissement', $etablissement);
        }


        if ($term) {
            $qb->andWhere('c.designation LIKE :term')
                ->setParameter('term', '%' . $term . '%');
        }

        return $qb->orderBy('c.designation', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    // src/Repository/ClasseRepository.php
    public function findByFilters($designation, $etablissement, $niveau, $taux)
    {
        $qb = $this->createQueryBuilder('c');

        // Calcul du taux de remplissage conditionnel
        $hasTauxFilter = $taux !== null;
        if ($hasTauxFilter) {
            $qb->addSelect('(c.effectif * 100.0) / NULLIF(c.capacite, 0) AS taux_remplissage');
        }

        if ($designation) {
            $qb->andWhere('c.designation LIKE :designation')
                ->setParameter('designation', '%' . $designation . '%');
        }

        if ($etablissement) {
            $qb->andWhere('c.etablissement = :etablissement')
                ->setParameter('etablissement', $etablissement);
        }

        if ($niveau) {
            $qb->andWhere('c.niveau = :niveau')
                ->setParameter('niveau', $niveau);
        }

        if ($hasTauxFilter) {
            switch ($taux) {
                case 'low':
                    $qb->andHaving('taux_remplissage < 50');
                    break;
                case 'medium':
                    $qb->andHaving('taux_remplissage BETWEEN 50 AND 80');
                    break;
                case 'high':
                    $qb->andHaving('taux_remplissage > 80');
                    break;
            }
        }

        $result = $qb->getQuery()->getResult();

        // Retourne uniquement les entités Classes si un filtre de taux est appliqué
        return $hasTauxFilter ? array_map(fn($row) => $row[0], $result) : $result;
    }

    public function findByFiltersAndEtablissement($designation, ?Etablissements $etablissement, $niveau, $taux)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c', '(c.effectif * 100.0) / NULLIF(c.capacite, 0) AS taux_remplissage');

        if ($designation) {
            $qb->andWhere('c.designation LIKE :designation')
                ->setParameter('designation', '%' . $designation . '%');
        }

        if ($etablissement) {
            $qb->andWhere('c.etablissement = :etablissement')
                ->setParameter('etablissement', $etablissement);
        }

        if ($niveau) {
            $qb->andWhere('c.niveau = :niveau')
                ->setParameter('niveau', $niveau);
        }

        if ($taux) {
            switch ($taux) {
                case 'low':
                    $qb->andHaving('taux_remplissage < 50');
                    break;
                case 'medium':
                    $qb->andHaving('taux_remplissage BETWEEN 50 AND 80');
                    break;
                case 'high':
                    $qb->andHaving('taux_remplissage > 80');
                    break;
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function findByEleveIds(array $eleveIds): array
    {
        if (empty($eleveIds)) {
            return [];
        }

        return $this->createQueryBuilder('c')
            ->distinct()
            ->innerJoin('c.eleves', 'e')
            ->where('e.id IN (:eleveIds)')
            ->setParameter('eleveIds', $eleveIds)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByEtablissement(Etablissements $etablissement): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.etablissement = :etablissement')
            ->setParameter('etablissement', $etablissement)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Classes[] Returns an array of Classes objects
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

    //    public function findOneBySomeField($value): ?Classes
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
