<?php

namespace App\Repository;

use App\Entity\DynastyTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DynastyTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DynastyTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DynastyTranslation[]    findAll()
 * @method DynastyTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DynastyTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DynastyTranslation::class);
    }

    // /**
    //  * @return DynastyTranslation[] Returns an array of DynastyTranslation objects
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
    public function findOneBySomeField($value): ?DynastyTranslation
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
