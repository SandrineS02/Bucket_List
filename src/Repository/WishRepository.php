<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wish>
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    public function findPublishedWishesWithCategories(): ?array
    {
        $queryBuilder = $this->createQueryBuilder('w');
        // On ajoute la jointure avec catégorie, pour éviter les multiples requêtes.
        // On n'oublie pas de sélectionner les données !
        $queryBuilder
            ->join('w.category', 'c')
            ->addSelect('c')
            ->andWhere('w.published = :published')
            ->setParameter('published', true)
            ->orderBy('w.dateCreated', 'DESC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function purge(int $nbMonths = 6): void
    {
        $this->createQueryBuilder('w')
            ->delete()
            ->where('w.published = 0 AND DATE_ADD(w.dateCreated, :nbMonths, \'MONTH\') <= CURRENT_TIMESTAMP()')
            ->setParameter('nbMonths', $nbMonths)
            ->getQuery()
            ->execute();
    }
    //    /**
    //     * @return Wish[] Returns an array of Wish objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Wish
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
