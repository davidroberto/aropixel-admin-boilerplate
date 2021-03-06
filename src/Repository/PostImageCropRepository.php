<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PostImageCrop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PostImageCrop|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostImageCrop|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostImageCrop[]    findAll()
 * @method PostImageCrop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostImageCropRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PostImageCrop::class);
    }

    // /**
    //  * @return PostImageCrop[] Returns an array of PostImageCrop objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostImageCrop
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
