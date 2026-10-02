<?php

namespace Kematjaya\WilayahBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kematjaya\WilayahBundle\Entity\Provinsi;

/**
 * @method Provinsi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Provinsi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Provinsi[]    findAll()
 * @method Provinsi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProvinsiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Provinsi::class);
    }
}
