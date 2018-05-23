<?php

namespace App\Repository;

use App\Entity\SMSIn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SMSIn|null find($id, $lockMode = null, $lockVersion = null)
 * @method SMSIn|null findOneBy(array $criteria, array $orderBy = null)
 * @method SMSIn[]    findAll()
 * @method SMSIn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SMSInRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SMSIn::class);
    }

//    /**
//     * @return SMSIn[] Returns an array of SMSIn objects
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
    public function findOneBySomeField($value): ?SMSIn
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
