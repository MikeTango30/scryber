<?php

namespace App\Repository;

use App\Entity\CreditLogActions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CreditLogActions|null find($id, $lockMode = null, $lockVersion = null)
 * @method CreditLogActions|null findOneBy(array $criteria, array $orderBy = null)
 * @method CreditLogActions[]    findAll()
 * @method CreditLogActions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreditLogActionsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CreditLogActions::class);
    }

    // /**
    //  * @return CreditLogActions[] Returns an array of CreditLogActions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CreditLogActions
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
