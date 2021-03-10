<?php

namespace App\Repository;

use App\Entity\Starships;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Starships|null find($id, $lockMode = null, $lockVersion = null)
 * @method Starships|null findOneBy(array $criteria, array $orderBy = null)
 * @method Starships[]    findAll()
 * @method Starships[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StarshipsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Starships::class);
    }

    // /**
    //  * @return Starships[] Returns an array of Starships objects
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
    public function findOneBySomeField($value): ?Starships
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
