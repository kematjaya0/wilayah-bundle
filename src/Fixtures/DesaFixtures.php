<?php

namespace Kematjaya\WilayahBundle\Fixtures;

use Doctrine\Persistence\ObjectManager;
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
    
    public function __construct(private KecamatanRepository $kecamatanRepo, private VillageSourceReaderInterface $villageSourceReader)
    {
    }
    
    public function load(ObjectManager $manager) :void
    {
        $kecamatans = $this->kecamatanRepo->findAll();
        foreach ($kecamatans as $kecamatan) {
            try {
                $villages = $this->villageSourceReader->filterByDistrictId($kecamatan->getCode(), $kecamatan->getKabupaten()->getCode());
            } catch (\Exception $ex) {
                dump($ex->getMessage());
                continue;
            }
            
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

    public function getDependencies() :array
    {
        return [
            WilayahFixtures::class
        ];
    }

}
