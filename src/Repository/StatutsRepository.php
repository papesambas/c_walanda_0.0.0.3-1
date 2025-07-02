<?php

namespace App\Repository;

use App\Entity\Statuts;
use App\Entity\Enseignements;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Statuts>
 */
class StatutsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statuts::class);
    }

        /**
     * Recherche de statuts par terme et enseignement
     *
     * @param string|null $term Terme de recherche
     * @param int|null $enseignementId ID de l'enseignement
     * @param int $limit Limite de résultats
     * @return Statuts[]
     */
    public function searchByEnseignement(?string $term = null, ?int $enseignementId = null, int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.designation', 'ASC')
            ->setMaxResults($limit);

        if ($term) {
            $qb->andWhere('s.designation LIKE :term')
                ->setParameter('term', '%' . $term . '%');
        }

        if ($enseignementId) {
            $qb->innerJoin('s.enseignement', 'e')
                ->andWhere('e.id = :enseignementId')
                ->setParameter('enseignementId', $enseignementId);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Trouve ou crée un statut par sa désignation
     */
    public function findOrCreate(string $designation, ?int $enseignementId = null): Statuts
    {
        $statut = $this->findOneBy(['designation' => $designation]);
        
        if (!$statut) {
            $statut = new Statuts();
            $statut->setDesignation($designation);
            
            if ($enseignementId) {
                $enseignement = $this->getEntityManager()
                    ->getReference(Enseignements::class, $enseignementId);
                
                if ($enseignement) {
                    $statut->addEnseignement($enseignement);
                }
            }
            
            $this->getEntityManager()->persist($statut);
            $this->getEntityManager()->flush();
        }
        
        return $statut;
    }

    /**
     * Récupère les statuts pour un enseignement donné
     */
    public function findByEnseignement(int $enseignementId): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.enseignement', 'e')
            ->andWhere('e.id = :enseignementId')
            ->setParameter('enseignementId', $enseignementId)
            ->orderBy('s.designation', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Statuts[] Returns an array of Statuts objects
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

    //    public function findOneBySomeField($value): ?Statuts
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
