<?php

namespace App\Repository;

use App\Entity\ThemeTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ThemeTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThemeTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThemeTranslation[]    findAll()
 * @method ThemeTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThemeTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThemeTranslation::class);
    }

    // /**
    //  * @return ThemeTranslation[] Returns an array of ThemeTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ThemeTranslation
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
