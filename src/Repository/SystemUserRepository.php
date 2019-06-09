<?php

namespace App\Repository;

use App\Entity\SystemUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SystemUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method SystemUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method SystemUser[]    findAll()
 * @method SystemUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SystemUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SystemUser::class);
    }

    // /**
    //  * @return SystemUser[] Returns an array of SystemUser objects
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
    public function findOneBySomeField($value): ?SystemUser
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
