<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

       /**
        * @return Article[] Returns an array of Article objects
        */
       public function findThreeByCategory($category, $id): array
       {
           return $this->createQueryBuilder('a')
               ->andWhere('a.category = :val')
               ->andWhere('a.id != :art')
               ->setParameter('val', $category) 
               ->setParameter('art', $id)
               ->orderBy('a.id', 'ASC')
               ->setMaxResults(3)
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
