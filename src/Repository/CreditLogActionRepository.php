<?php

namespace App\Repository;

use App\Entity\CreditLogAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CreditLogAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method CreditLogAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method CreditLogAction[]    findAll()
 * @method CreditLogAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreditLogActionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CreditLogAction::class);
    }

    // /**
    //  * @return CreditLogAction[] Returns an array of CreditLogAction objects
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
    public function findOneBySomeField($value): ?CreditLogAction
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
