<?php

namespace App\Repository;

use App\Entity\MaterialTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MaterialTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialTranslation[]    findAll()
 * @method MaterialTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialTranslation::class);
    }

    // /**
    //  * @return MaterialTranslation[] Returns an array of MaterialTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MaterialTranslation
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
