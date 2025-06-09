<?php

namespace App\Repository;

use App\Entity\Peres;
use App\Data\SearchData;
use App\Data\SearchParentData;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

             public function findBySearchData(SearchData $searchData)
     {
         $queryBuilder = $this->createQueryBuilder('p')
             ->select('p', 'pr', 't1', 't2', 'n')
             ->leftJoin('p.profession', 'pr')
             ->leftJoin('p.telephone1', 't1')
             ->leftJoin('p.telephone2', 't2')
             ->leftJoin('p.nina', 'n');
     
         // Recherche sur le champ "q"
         if (!empty($searchData->q)) {
             $terms = explode(' ', $searchData->q); // Découper la recherche en mots
             foreach ($terms as $key => $term) {
                 $normalizedTerm = mb_strtolower($term, 'UTF-8');
                 $normalizedTerm = iconv('UTF-8', 'ASCII//TRANSLIT', $normalizedTerm); // Supprimer les accents
     
                 $queryBuilder
                     ->andWhere('LOWER(p.fullname) LIKE :term' . $key)
                     ->setParameter('term' . $key, '%' . $normalizedTerm . '%');
             }
         }
     
         // Filtre par profession
         if (!empty($searchData->professions)) {
             $queryBuilder
                 ->andWhere('p.profession IN (:professions)')
                 ->setParameter('professions', $searchData->professions);
         }
     
         // Recherche par téléphone
         if (!empty($searchData->telephone)) {
             $normalizedTelephone = preg_replace('/\D/', '', $searchData->telephone);
     
             $queryBuilder
                 ->andWhere('t1.numero LIKE :telephone OR t2.numero LIKE :telephone')
                 ->setParameter('telephone', '%' . $normalizedTelephone . '%');
         }
     
         // Recherche par NINA
         if (!empty($searchData->nina)) {
             $queryBuilder
                 ->andWhere('n.designation LIKE :nina')
                 ->setParameter('nina', '%' . $searchData->nina . '%');
         }
     
         return $queryBuilder->getQuery()->getResult();
     }

    /**
     * Recherche des entités Peres en fonction des critères de SearchParentData.
     *
     * @param SearchParentData $searchParentData Les critères de recherche.
     * @return Peres[] Les résultats de la recherche.
     */

     public function findBySearchParentData(SearchParentData $searchParentData)
     {
         // Si SearchParentData est null ou que ses propriétés sont vides, retourner une liste vide
         if (
             $searchParentData === null ||
             (empty($searchParentData->qPere) && empty($searchParentData->telephonePere) && empty($searchParentData->ninaPere))
         ) {
             return [];
         }
     
         $queryBuilder = $this->createQueryBuilder('p')
             ->leftJoin('p.telephone1', 't1')
             ->leftJoin('p.telephone2', 't2')
             ->leftJoin('p.nina', 'n')
             ->addSelect('t1', 't2', 'n');
     
         // Recherche par fullname (insensible à la casse)
         if (!empty($searchParentData->qPere)) {
             $searchTerm = trim($searchParentData->qPere); // Supprimer les espaces inutiles
             $queryBuilder
                 ->andWhere('LOWER(p.fullname) LIKE LOWER(:fullname)')
                 ->setParameter('fullname', '%' . $searchTerm . '%')
                 ;
         }
     
         // Recherche par téléphone
         if (!empty($searchParentData->telephonePere)) {
             $normalizedTelephone = preg_replace('/\D/', '', $searchParentData->telephonePere);
             $queryBuilder
                 ->andWhere('t1.numero LIKE :telephonePere OR t2.numero LIKE :telephonePere')
                 ->setParameter('telephonePere', '%' . $normalizedTelephone . '%');
         }
     
         // Recherche par NINA
         if (!empty($searchParentData->ninaPere)) {
             $queryBuilder
                 ->andWhere('n.designation LIKE :ninaPere')
                 ->setParameter('ninaPere', '%' . $searchParentData->ninaPere . '%');
         }
     
         return $queryBuilder->getQuery()->getResult();
     }     

     public function findBySearchParentDataForInscription(SearchParentData $searchParentData)
     {
         // Si SearchParentData est null ou que ses propriétés sont vides, retourner une liste vide
         if (
             $searchParentData === null ||
             (empty($searchParentData->telephonePere) && empty($searchParentData->ninaPere))
         ) {
             return [];
         }
     
         $queryBuilder = $this->createQueryBuilder('p')
             ->leftJoin('p.telephone1', 't1')
             ->leftJoin('p.telephone2', 't2')
             ->leftJoin('p.nina', 'n')
             ->addSelect('t1', 't2', 'n');
     
         // Recherche par nom (commence par le terme recherché)
         if (!empty($searchParentData->qPere)) {
             $queryBuilder
                 ->andWhere('LOWER(p.fullname) LIKE LOWER(:qPere)')
                 ->setParameter('qPere', strtolower($searchParentData->qPere) . '%');
         }
     
         // Recherche par téléphone
         if (!empty($searchParentData->telephonePere)) {
             $normalizedTelephone = preg_replace('/\D/', '', $searchParentData->telephonePere);
             $queryBuilder
                 ->andWhere('t1.numero LIKE :telephonePere OR t2.numero LIKE :telephonePere')
                 ->setParameter('telephonePere', '%' . $normalizedTelephone . '%');
         }
     
         // Recherche par NINA
         if (!empty($searchParentData->ninaPere)) {
             $queryBuilder
                 ->andWhere('n.designation LIKE :ninaPere')
                 ->setParameter('ninaPere', '%' . $searchParentData->ninaPere . '%');
         }
     
         return $queryBuilder->getQuery()->getResult();
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
