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
        $provinsi = $this->provinceSourceReader->read();
        $kabKota = $this->regionSourceReader->read();
        $kecamatans = $this->districtSourceReader->read();
        
        if (!empty($this->configs['provinsi'])) {
            $provinsi = array_filter($provinsi, function ($row) {
                
                return in_array($row['id'], $this->configs['provinsi']);
            });
        }
        
        if (!empty($this->configs['kabupaten'])) {
            $kabKota = array_filter($kabKota, function ($row) {
                
                return in_array($row['kode'], $this->configs['kabupaten']);
            });
        }
        
        if (!empty($this->configs['kecamatan'])) {
            $kecamatans = array_filter($kecamatans, function ($row) {
                
                return in_array($row['kode'], $this->configs['kecamatan']);
            });
        }
        
        foreach ($provinsi as $prov) {
            $object = new Provinsi();
            $object->setCode($prov['id'])
                    ->setName($prov['nama']);
            
            $kabupaten = array_filter($kabKota, function ($row) use ($object) {
                
                return (preg_match("/^" . $object->getCode() . "/i", $row['kode']));
            });
            
            $manager->persist($object);
            foreach ($kabupaten as $kab) {
                $kabObject = new Kabupaten();
                $kabObject->setProvinsi($object)
                        ->setCode($kab['kode'])
                        ->setName($kab['nama']);
                $kecs = array_filter($kecamatans, function ($kecRow) use ($kabObject) {
                
                    return (preg_match("/^" . $kabObject->getCode() . "/i", $kecRow['kode']));
                });
                
                $manager->persist($kabObject);
                
                foreach ($kecs as $kec) {
                    $kecObject = new Kecamatan();
                    $kecObject->setKabupaten($kabObject)
                            ->setCode($kec['kode'])
                            ->setName($kec['nama']);
                    
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
