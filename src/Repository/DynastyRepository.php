<?php

namespace App\Repository;

use App\Entity\Dynasty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dynasty|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dynasty|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dynasty[]    findAll()
 * @method Dynasty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DynastyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dynasty::class);
    }

    // /**
    //  * @return Dynasty[] Returns an array of Dynasty objects
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
    public function findOneBySomeField($value): ?Dynasty
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
