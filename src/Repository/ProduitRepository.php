<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }




public function findByLowStock(int $threshold): array
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.qteStock <= :val') 
        ->setParameter('val', $threshold)
        ->orderBy('p.qteStock', 'ASC')
        ->getQuery()
        ->getResult();
}

/**public function getTotalInventoryValue(): float
{
    // Check if your price field is 'prix'. If it's different, change it here!
    $result = $this->createQueryBuilder('p')
        ->select('SUM(p.prix * p.qteStock)') 
        ->getQuery()
        ->getSingleScalarResult();

    return (float) $result;
}
    //    /**
    //     * @return Produit[] Returns an array of Produit objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
