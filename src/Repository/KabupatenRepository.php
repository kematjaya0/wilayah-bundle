<?php

namespace Kematjaya\WilayahBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kematjaya\WilayahBundle\Entity\Kabupaten;

/**
 * @method Kabupaten|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kabupaten|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kabupaten[]    findAll()
 * @method Kabupaten[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KabupatenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kabupaten::class);
    }
}
