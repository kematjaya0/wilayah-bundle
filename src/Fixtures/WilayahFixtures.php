<?php

namespace Kematjaya\WilayahBundle\Fixtures;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Kematjaya\WilayahBundle\SourceReader\DistrictSourceReaderInterface;
use Kematjaya\WilayahBundle\SourceReader\RegionSourceReaderInterface;
use Kematjaya\WilayahBundle\SourceReader\ProvinceSourceReaderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @package Kematjaya\WIlayahBundle\Fixtures
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class WilayahFixtures extends Fixture implements FixtureGroupInterface
{
    private $configs = [];

    public function __construct(ParameterBagInterface $bag, private EntityManagerInterface $em, private DistrictSourceReaderInterface $districtSourceReader, private RegionSourceReaderInterface $regionSourceReader, private ProvinceSourceReaderInterface $provinceSourceReader)
    {
        $configs = $bag->get('wilayah');
        $this->configs = $configs['filter'];
    }

    public function load(ObjectManager $manager) :void
    {
        $con = $this->em->getConnection();
        $provinsis = $this->provinceSourceReader->findAll(
            $this->configs['provinsi']
        );
        foreach ($provinsis as $prov) {
            $provId = (string)Uuid::v7();
            $con->insert('provinsi', [
                'id' => $provId,
                'code' => $prov['id'],
                'name' => strtoupper($prov['nama']),
            ]);

            $kabupatens = $this->regionSourceReader->filterByProvinceId($prov['id'], $this->configs['kabupaten']);
            foreach ($kabupatens as $kabupaten) {
                $kabId = (string)Uuid::v7();
                $con->insert('kabupaten', [
                    'id' => $kabId,
                    'code' => $kabupaten['id'],
                    'name' => strtoupper($kabupaten['nama']),
                    'provinsi_id' => $provId
                ]);
                $kecamatans = $this->districtSourceReader->filterByRegionId($kabupaten['id'], $this->configs['kecamatan']);
                foreach ($kecamatans as $kecamatan) {
                    $con->insert('kecamatan', [
                        'id' => (string)Uuid::v7(),
                        'code' => $kecamatan['id'],
                        'name' => strtoupper($kecamatan['nama']),
                        'kabupaten_id' => $kabId
                    ]);
                }

                $manager->flush();
            }
        }
    }

    public static function getGroups(): array
    {
        return ['wilayah'];
    }
}
