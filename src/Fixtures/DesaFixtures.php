<?php

/**
 * This file is part of the wilayah-bundle.
 */

namespace Kematjaya\WilayahBundle\Fixtures;

use Kematjaya\WilayahBundle\Entity\Desa;
use Kematjaya\WilayahBundle\Repository\KecamatanRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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
    
    private $configs = [];
    
    public function __construct(KecamatanRepository $kecamatanRepo, ParameterBagInterface $bag) 
    {
        $this->kecamatanRepo = $kecamatanRepo;
        $configs = $bag->get('wilayah');
        $this->configs = $configs['filter'];
    }
    
    public function load(\Doctrine\Persistence\ObjectManager $manager) 
    {dump($this->configs);exit;
        $location = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Resources/data';
        $kelurahans = json_decode(
            file_get_contents($location . DIRECTORY_SEPARATOR . 'kelurahan.json'), true
        );
        $kecamatans = $this->kecamatanRepo->findAll();
        foreach ($kecamatans as $kecamatan) {
            $kels = array_filter($kelurahans, function ($kecRow) use ($kecamatan) {
                
                return (preg_match("/^" . $kecamatan->getCode() . "/i", $kecRow['kode']));
            });
            foreach ($kels as $kel) {
                $kelurahan = new Desa();
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
        return [
            WilayahFixtures::class
        ];
    }

}
