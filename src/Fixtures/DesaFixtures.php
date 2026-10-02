<?php

namespace Kematjaya\WilayahBundle\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Kematjaya\WilayahBundle\Repository\KecamatanRepository;
use Kematjaya\WilayahBundle\SourceReader\VillageSourceReaderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @package Kematjaya\WilayahBundle\Fixtures
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class DesaFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private readonly array $configs;
    public function __construct(
        ParameterBagInterface $bag,
        private readonly EntityManagerInterface $em,
        private readonly KecamatanRepository $kecamatanRepo,
        private readonly VillageSourceReaderInterface $villageSourceReader,
    ) {
        $configs = $bag->get('wilayah');
        $this->configs = $configs['filter'];
    }

    public function load(ObjectManager $manager): void
    {
        $con = $this->em->getConnection();
        $kecamatans = $this->kecamatanRepo->createQueryBuilder('t')
            ->select('t.id, t.code')->getQuery()->getResult();
        foreach ($kecamatans as $row) {
            try {
                $villages = $this->villageSourceReader->filterByDistrictId($row['code'], $this->configs['desa']);
            } catch (\Exception $ex) {
                dump($ex->getMessage());
                continue;
            }

            foreach ($villages as $village) {
                $con->insert('desa', [
                    'id' => (string) Uuid::v7(),
                    'code' => $village['id'],
                    'name' => strtoupper($village['nama']),
                    'kecamatan_id' => $row['id'],
                ]);
            }

            $manager->flush();
        }
    }

    public static function getGroups(): array
    {
        return ['wilayah'];
    }

    public function getDependencies(): array
    {
        return [
            WilayahFixtures::class,
        ];
    }

}
