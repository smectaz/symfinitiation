<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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
//langage dql
    public function findByCategory($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.category = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneById($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

///**
    //* @return Article[] Returns an array of Article objects
    //*/
    //langage dql
    /*   public function findByCategory($value)
{
return $this->createQueryBuilder('a')
->andWhere('a.category = :val')
->setParameter('val', $value)
->orderBy('a.id', 'ASC')
->setMaxResults(10)
->getQuery()
->getResult()
;
}

public function findOneById($value): ?Article
{
return $this->createQueryBuilder('a')
->andWhere('a.id = :val')
->setParameter('val', $value)
->getQuery()
->getOneOrNullResult()
;
} */

}
