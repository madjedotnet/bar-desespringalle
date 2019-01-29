<?php

namespace App\Repository;

use App\Entity\AlbumLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AlbumLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlbumLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlbumLike[]    findAll()
 * @method AlbumLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumLikeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AlbumLike::class);
    }

//    /**
//     * @return AlbumLike[] Returns an array of AlbumLike objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AlbumLike
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
