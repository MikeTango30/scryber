<?php

namespace App\Repository;

use App\Entity\UserFile;
use App\Service\Doctrine\UseIndexWalker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFile[]    findAll()
 * @method UserFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserFile::class);
    }

    /**
     * @param $value
     * @param int $currentPage
     * @param int $perPage
     * @return \ArrayIterator|\Traversable
     */
    public function getUserfilesSorted($value, $currentPage = 1, $perPage = 15)
    {
        $sortedList = $this->createQueryBuilder('uf')
            ->innerJoin('uf.user', 'u')
            ->select('uf')
            ->andWhere('uf.user = :val')
            ->setParameter('val', $value)
            ->orderBy('uf.created', 'DESC')
            ;

        return $this->paginate($sortedList->getQuery(), $currentPage, $perPage)->getIterator();
    }

    /**
     * @param $value
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUserfilesTotal($value)
    {
        $userfilesTotal = $this->createQueryBuilder('uf')
            ->where('uf.user = :val')
            ->select('COUNT(uf.id)')
            ->setParameter('val', $value)
            ->getQuery()
            ->getSingleScalarResult();

        return $userfilesTotal;
    }


    /**
     * @param $dql
     * @param int $page
     * @param int $limit
     * @return Paginator
     */
    public function paginate($dql, $page = 1, $limit = 100)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

    // /**
    //  * @return UserFile[] Returns an array of UserFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserFile
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
