<?php

/**
 * This file is part of the wilayah-bundle.
 */

namespace Kematjaya\WilayahBundle\Fixtures;

use Kematjaya\WilayahBundle\SourceReader\KelurahanSourceReaderInterface;
use Kematjaya\WilayahBundle\Repository\KecamatanRepository;
use Kematjaya\WilayahBundle\Entity\Kelurahan;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * @package Kematjaya\WilayahBundle\Fixtures
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class KelurahanFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    /**
     * 
     * @var KecamatanRepository
     */
    private $kecamatanRepo;
    
    /**
     * 
     * @var KelurahanSourceReaderInterface
     */
    private $kelurahanSourceReader;
    
    public function __construct(KecamatanRepository $kecamatanRepo, KelurahanSourceReaderInterface $kelurahanSourceReader) 
    {
        $this->kecamatanRepo = $kecamatanRepo;
        $this->kelurahanSourceReader = $kelurahanSourceReader;
    }
    
    public function load(\Doctrine\Persistence\ObjectManager $manager) 
    {
//        $kelurahans = $this->kelurahanSourceReader->read();
//        $kecamatans = $this->kecamatanRepo->findAll();
//        foreach ($kecamatans as $kecamatan) {
//            $kels = array_filter($kelurahans, function ($kecRow) use ($kecamatan) {
//                
//                return (preg_match("/^" . $kecamatan->getCode() . "/i", $kecRow['kode']));
//            });
//            foreach ($kels as $kel) {
//                $kelurahan = new Kelurahan();
//                $kelurahan->setCode($kel['kode'])
//                        ->setName($kel['nama'])
//                        ->setKecamatan($kecamatan);
//                
//                $manager->persist($kelurahan);
//            }
//        }
//        
//        $manager->flush();
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
