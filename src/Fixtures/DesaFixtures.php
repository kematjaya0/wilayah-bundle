<?php

/**
 * This file is part of the wilayah-bundle.
 */

namespace Kematjaya\WilayahBundle\Fixtures;

use Kematjaya\WilayahBundle\Entity\Desa;
use Kematjaya\WilayahBundle\SourceReader\VillageSourceReaderInterface;
use Kematjaya\WilayahBundle\Repository\KecamatanRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * @package Kematjaya\WilayahBundle\Fixtures
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class DesaFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface 
{
    
    /**
     * 
     * @var KecamatanRepository
     */
    private $kecamatanRepo;
    
    /**
     * 
     * @var VillageSourceReaderInterface
     */
    private $villageSourceReader;
    
    public function __construct(KecamatanRepository $kecamatanRepo, VillageSourceReaderInterface $villageSourceReader) 
    {
        $this->kecamatanRepo = $kecamatanRepo;
        $this->villageSourceReader = $villageSourceReader;
    }
    
    public function load(\Doctrine\Persistence\ObjectManager $manager) 
    {
        $kecamatans = $this->kecamatanRepo->findAll();
        foreach ($kecamatans as $kecamatan) {
            $villages = $this->villageSourceReader->filterByDistrictId($kecamatan->getCode());
            foreach ($villages as $village) {
                $kelurahan = new Desa();
                $kelurahan->setCode($village['id'])
                        ->setName(strtoupper($village['nama']))
                        ->setKecamatan($kecamatan);
                
                $manager->persist($kelurahan);
            }
        }
        
        $manager->flush();
    }
    
    public static function getGroups(): array 
    {
        return ['wilayah'];
    }

    public function getDependencies() 
    {
        return [
            WilayahFixtures::class
        ];
    }

}
