<?php

/**
 * This file is part of the wilayah-bundle.
 */

namespace Kematjaya\WilayahBundle\Fixtures;

use Kematjaya\WilayahBundle\Entity\Kecamatan;
use Kematjaya\WilayahBundle\Entity\Kabupaten;
use Kematjaya\WilayahBundle\Entity\Provinsi;
use Kematjaya\WilayahBundle\SourceReader\DistrictSourceReaderInterface;
use Kematjaya\WilayahBundle\SourceReader\RegionSourceReaderInterface;
use Kematjaya\WilayahBundle\SourceReader\ProvinceSourceReaderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @package Kematjaya\WIlayahBundle\Fixtures
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class WilayahFixtures extends Fixture implements FixtureGroupInterface
{
    private $configs = [];
    
    /**
     * 
     * @var ProvinceSourceReaderInterface
     */
    private $provinceSourceReader;
    
    /**
     * 
     * @var RegionSourceReaderInterface
     */
    private $regionSourceReader;
    
    /**
     * 
     * @var DistrictSourceReaderInterface
     */
    private $districtSourceReader;
    
    public function __construct(ParameterBagInterface $bag, DistrictSourceReaderInterface $districtSourceReader, RegionSourceReaderInterface $regionSourceReader, ProvinceSourceReaderInterface $provinceSourceReader) 
    {
        $configs = $bag->get('wilayah');
        $this->configs = $configs['filter'];
        $this->provinceSourceReader = $provinceSourceReader;
        $this->regionSourceReader = $regionSourceReader;
        $this->districtSourceReader = $districtSourceReader;
    }
    
    public function load(\Doctrine\Persistence\ObjectManager $manager) 
    {
        $provinsis = $this->provinceSourceReader->findAll(
            $this->configs['provinsi']
        );
        foreach ($provinsis as $prov) {
            $object = new Provinsi();
            $object->setCode($prov['id'])
                    ->setName(strtoupper($prov['nama']));
            
            $manager->persist($object);
            $kabupatens = $this->regionSourceReader->filterByProvinceId($prov['id'], $this->configs['kabupaten']);
            foreach ($kabupatens as $kabupaten) {
                $kabObject = new Kabupaten();
                $kabObject->setProvinsi($object)
                        ->setCode($kabupaten['id'])
                        ->setName(strtoupper($kabupaten['nama']));
                
                $manager->persist($kabObject);
                $kecamatans = $this->districtSourceReader->filterByRegionId($kabupaten['id'], $this->configs['kecamatan']);
                foreach ($kecamatans as $kecamatan) {
                    $kecObject = new Kecamatan();
                    $kecObject->setKabupaten($kabObject)
                            ->setCode($kecamatan['id'])
                            ->setName(strtoupper($kecamatan['nama']));
                    
                    $manager->persist($kecObject);
                }
            }
        }
        
        $manager->flush();
    }
    
    public static function getGroups(): array 
    {
        return ['wilayah'];
    }
}
