<?php

namespace App\Repository;

use App\Entity\NavigationTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NavigationTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method NavigationTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method NavigationTranslation[]    findAll()
 * @method NavigationTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NavigationTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NavigationTranslation::class);
    }

    // /**
    //  * @return NavigationTranslation[] Returns an array of NavigationTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NavigationTranslation
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
