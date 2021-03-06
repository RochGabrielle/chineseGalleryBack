<?php

namespace App\Repository;

use App\Entity\Sizecategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sizecategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sizecategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sizecategory[]    findAll()
 * @method Sizecategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SizecategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SizeCategory::class);
    }

    // /**
    //  * @return Sizecategory[] Returns an array of Sizecategory objects
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
    public function findOneBySomeField($value): ?Sizecategory
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
