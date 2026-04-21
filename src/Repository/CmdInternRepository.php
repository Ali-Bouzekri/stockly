<?php

namespace App\Repository;


use App\Entity\CmdIntern;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CmdIntern>
 */
class CmdInternRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmdIntern::class);
    }
    public function findMaxNumero(): int
{
    $result = $this->createQueryBuilder('c')
        ->select('MAX(c.numero)')
        ->getQuery()
        ->getSingleScalarResult();

    return (int) $result;
}


}
