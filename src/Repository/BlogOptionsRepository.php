<?php

namespace App\Repository;

use App\Entity\BlogOptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogOptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogOptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogOptions[]    findAll()
 * @method BlogOptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogOptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogOptions::class);
    }

    /**
     * @return BlogOptions[] Returns an array of BlogOptions objects
     */

    public function findByDataKey($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.datakey = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
public function findOneBySomeField($value): ?BlogOptions
{
return $this->createQueryBuilder('b')
->andWhere('b.exampleField = :val')
->setParameter('val', $value)
->getQuery()
->getOneOrNullResult()
;
}
 */
}