<?php

namespace Kematjaya\WilayahBundle\Repository;

use Kematjaya\WilayahBundle\Entity\Desa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Desa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Desa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Desa[]    findAll()
 * @method Desa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DesaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Desa::class);
    }
}
