<?php

namespace App\Repository;

use App\Entity\FormTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormTranslation[]    findAll()
 * @method FormTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormTranslation::class);
    }

    // /**
    //  * @return FormTranslation[] Returns an array of FormTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FormTranslation
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
