<?php

/**
 * This file is part of the wilayah-bundle.
 */

namespace Kematjaya\WilayahBundle\Fixtures;

use Kematjaya\WilayahBundle\Entity\Kecamatan;
use Kematjaya\WilayahBundle\Entity\Kabupaten;
use Kematjaya\WilayahBundle\Entity\Provinsi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * @package Kematjaya\WIlayahBundle\Fixtures
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class WilayahFixtures extends Fixture implements FixtureGroupInterface
{
    
    public function load(\Doctrine\Persistence\ObjectManager $manager) 
    {
        $location = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Resources/data';
        $provinsi = json_decode(
            file_get_contents($location . DIRECTORY_SEPARATOR . 'provinsi.json'), true
        );
        $kabKota = json_decode(
            file_get_contents($location . DIRECTORY_SEPARATOR . 'kabupaten.json'), true
        );
        $kecamatans = json_decode(
            file_get_contents($location . DIRECTORY_SEPARATOR . 'kecamatan.json'), true
        );
        
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
