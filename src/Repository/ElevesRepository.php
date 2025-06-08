<?php

namespace App\Repository;

use App\Entity\Cercles;
use App\Entity\Classes;
use App\Entity\Communes;
use App\Entity\Eleves;
use App\Entity\Etablissements;
use App\Entity\LieuNaissances;
use App\Entity\Regions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Eleves>
 */
class ElevesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eleves::class);
    }

    public function findByFilters(?string $fullname, ?Etablissements $etablissements, ?int $classeId, ?int $statutId): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.classe', 'c')
            ->leftJoin('e.statut', 's');

        if ($etablissements) {
            $qb->andWhere('e.etablissement = :etablissement')
                ->setParameter('etablissement', $etablissements);
        }

        if ($fullname) {
            $qb->andWhere('e.fullname LIKE :fullname')
                ->setParameter('fullname', '%' . $fullname . '%');
        }

        if ($classeId) {
            $qb->andWhere('c.id = :classeId')
                ->setParameter('classeId', $classeId);
        }

        if ($statutId) {
            $qb->andWhere('s.id = :statutId')
                ->setParameter('statutId', $statutId);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByFiltersAndLieuNaissance(?string $fullname, ?LieuNaissances $lieuNaissances, ?Etablissements $etablissements, ?int $classeId, ?int $statutId): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.classe', 'c')
            ->leftJoin('e.statut', 's');

        if ($etablissements) {
            $qb->andWhere('e.etablissement = :etablissement')
                ->setParameter('etablissement', $etablissements);
        }

        if ($lieuNaissances) {
            $qb->andWhere('e.lieuNaissance = :lieuNaissances')
                ->setParameter('lieuNaissances', $lieuNaissances);
        }


        if ($fullname) {
            $qb->andWhere('e.fullname LIKE :fullname')
                ->setParameter('fullname', '%' . $fullname . '%');
        }

        if ($classeId) {
            $qb->andWhere('c.id = :classeId')
                ->setParameter('classeId', $classeId);
        }

        if ($statutId) {
            $qb->andWhere('s.id = :statutId')
                ->setParameter('statutId', $statutId);
        }

        return $qb->getQuery()->getResult();
    }


    public function findByFiltersAndCommune(?string $fullname, ?Communes $communes, ?Etablissements $etablissements, ?int $classeId, ?int $statutId): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.classe', 'c')
            ->leftJoin('e.statut', 's');

        if ($etablissements) {
            $qb->andWhere('e.etablissement = :etablissement')
                ->setParameter('etablissement', $etablissements);
        }

        if ($communes) {
            $qb->leftJoin('e.lieuNaissance', 'l')
                ->andWhere('l.commune = :commune')
                ->setParameter('commune', $communes);
        }


        if ($fullname) {
            $qb->andWhere('e.fullname LIKE :fullname')
                ->setParameter('fullname', '%' . $fullname . '%');
        }

        if ($classeId) {
            $qb->andWhere('c.id = :classeId')
                ->setParameter('classeId', $classeId);
        }

        if ($statutId) {
            $qb->andWhere('s.id = :statutId')
                ->setParameter('statutId', $statutId);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByFiltersAndCercle(?string $fullname, ?Cercles $cercles, ?Etablissements $etablissements, ?int $classeId, ?int $statutId): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.classe', 'c')
            ->leftJoin('e.statut', 's');

        if ($etablissements) {
            $qb->andWhere('e.etablissement = :etablissement')
                ->setParameter('etablissement', $etablissements);
        }

        if ($cercles) {
            $qb->leftJoin('e.lieuNaissance', 'l')
                ->leftJoin('l.commune', 'co')
                ->andWhere('co.cercle = :cercle')
                ->setParameter('cercle', $cercles);
        }


        if ($fullname) {
            $qb->andWhere('e.fullname LIKE :fullname')
                ->setParameter('fullname', '%' . $fullname . '%');
        }

        if ($classeId) {
            $qb->andWhere('c.id = :classeId')
                ->setParameter('classeId', $classeId);
        }

        if ($statutId) {
            $qb->andWhere('s.id = :statutId')
                ->setParameter('statutId', $statutId);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByFiltersAndRegion(?string $fullname, ?Regions $regions, ?Etablissements $etablissements, ?int $niveauId, ?int $statutId): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.classe', 'c')
            ->leftJoin('e.statut', 's');

        if ($etablissements) {
            $qb->andWhere('e.etablissement = :etablissement')
                ->setParameter('etablissement', $etablissements);
        }

        if ($regions) {
            $qb->leftJoin('e.lieuNaissance', 'l')
                ->leftJoin('l.commune', 'co')
                ->leftJoin('co.cercle', 'ce')
                ->andWhere('ce.region = :region')
                ->setParameter('region', $regions);
        }


        if ($fullname) {
            $qb->andWhere('e.fullname LIKE :fullname')
                ->setParameter('fullname', '%' . $fullname . '%');
        }

        if ($niveauId) {
            $qb->leftJoin('e.classe', 'cl')
                ->andWhere('cl.id = :niveauId')
                ->setParameter('niveauId', $niveauId);
        }

        if ($statutId) {
            $qb->andWhere('s.id = :statutId')
                ->setParameter('statutId', $statutId);
        }

        return $qb->getQuery()->getResult();
    }

        public function findByFiltersAndClasse(?string $fullname, ?Classes $classes, ?Etablissements $etablissements, ?int $niveauId, ?int $statutId): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.classe', 'c')
            ->leftJoin('e.statut', 's');

        if ($etablissements) {
            $qb->andWhere('e.etablissement = :etablissement')
                ->setParameter('etablissement', $etablissements);
        }

        if ($classes) {
            $qb->andWhere('e.classe = :classe')
                ->setParameter('classe', $classes);
        }


        if ($fullname) {
            $qb->andWhere('e.fullname LIKE :fullname')
                ->setParameter('fullname', '%' . $fullname . '%');
        }

        if ($niveauId) {
            $qb->leftJoin('e.classe', 'cl')
                ->andWhere('cl.id = :niveauId')
                ->setParameter('niveauId', $niveauId);
        }

        if ($statutId) {
            $qb->andWhere('s.id = :statutId')
                ->setParameter('statutId', $statutId);
        }

        return $qb->getQuery()->getResult();
    }

        public function findByFiltersAndEtablissement(?string $fullname, ?Etablissements $etablissements, ?int $niveauId, ?int $statutId): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.classe', 'c')
            ->leftJoin('e.statut', 's');

        if ($etablissements) {
            $qb->andWhere('e.etablissement = :etablissement')
                ->setParameter('etablissement', $etablissements);
        }

        if ($fullname) {
            $qb->andWhere('e.fullname LIKE :fullname')
                ->setParameter('fullname', '%' . $fullname . '%');
        }

        if ($niveauId) {
            $qb->leftJoin('e.classe', 'cl')
                ->andWhere('cl.id = :niveauId')
                ->setParameter('niveauId', $niveauId);
        }

        if ($statutId) {
            $qb->andWhere('s.id = :statutId')
                ->setParameter('statutId', $statutId);
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Eleves[] Returns an array of Eleves objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Eleves
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
