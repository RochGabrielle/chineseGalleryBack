<?php

namespace App\Repository;

use App\Entity\SlideshowTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SlideshowTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SlideshowTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SlideshowTranslation[]    findAll()
 * @method SlideshowTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlideshowTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SlideshowTranslation::class);
    }

    // /**
    //  * @return SlideshowTranslation[] Returns an array of SlideshowTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SlideshowTranslation
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
