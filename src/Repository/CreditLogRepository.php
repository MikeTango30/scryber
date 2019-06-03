<?php

namespace App\Repository;

use App\Entity\CreditLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CreditLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method CreditLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method CreditLog[]    findAll()
 * @method CreditLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreditLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CreditLog::class);
    }

    // /**
    //  * @return CreditLog[] Returns an array of CreditLog objects
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
    public function findOneBySomeField($value): ?CreditLog
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
