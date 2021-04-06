<?php

/**
 * This file is part of the wilayah-bundle.
 */

namespace Kematjaya\WilayahBundle\Fixtures;

use Kematjaya\WilayahBundle\Entity\Kelurahan;
use Kematjaya\WilayahBundle\Entity\Kecamatan;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * @package Kematjaya\WilayahBundle\Fixtures
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class KelurahanFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    
    public function load(\Doctrine\Persistence\ObjectManager $manager) 
    {
        $content = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'kelurahan.json');
        
        $kelurahans = json_decode($content, true);
        
        $kecamatans = $manager->getRepository(Kecamatan::class)->findAll();
        foreach ($kecamatans as $kecamatan) {
            $kels = array_filter($kelurahans, function ($kecRow) use ($kecamatan) {
                
                return (preg_match("/^" . $kecamatan->getCode() . "/i", $kecRow['kode']));
            });
            foreach ($kels as $kel) {
                $kelurahan = new Kelurahan();
                $kelurahan->setCode($kel['kode'])
                        ->setName($kel['nama'])
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
        return [WilayahFixtures::class];
    }

}
