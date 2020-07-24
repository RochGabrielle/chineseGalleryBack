<?php

namespace App\Repository;

use App\Entity\DiscountTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DiscountTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscountTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscountTranslation[]    findAll()
 * @method DiscountTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscountTranslation::class);
    }

    // /**
    //  * @return DiscountTranslation[] Returns an array of DiscountTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DiscountTranslation
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
