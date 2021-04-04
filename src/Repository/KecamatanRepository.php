<?php

namespace Kematjaya\WilayahBundle\Repository;

use Kematjaya\WilayahBundle\Entity\Kecamatan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Kecamatan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kecamatan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kecamatan[]    findAll()
 * @method Kecamatan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KecamatanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kecamatan::class);
    }
}
