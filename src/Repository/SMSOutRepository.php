<?php

namespace App\Repository;

use App\Entity\SMSOut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SMSOut|null find($id, $lockMode = null, $lockVersion = null)
 * @method SMSOut|null findOneBy(array $criteria, array $orderBy = null)
 * @method SMSOut[]    findAll()
 * @method SMSOut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SMSOutRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SMSOut::class);
    }

//    /**
//     * @return SMSOut[] Returns an array of SMSOut objects
//     */
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
    public function findOneBySomeField($value): ?SMSOut
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
