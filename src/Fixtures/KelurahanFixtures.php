<?php


namespace Kematjaya\WilayahBundle\Fixtures;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Kematjaya\WilayahBundle\SourceReader\KelurahanSourceReaderInterface;
use Kematjaya\WilayahBundle\Repository\KecamatanRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @package Kematjaya\WilayahBundle\Fixtures
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class KelurahanFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private array $configs = [];
    public function __construct(ParameterBagInterface $bag, private EntityManagerInterface $em, private KecamatanRepository $kecamatanRepo, private KelurahanSourceReaderInterface $kelurahanSourceReader)
    {
        $this->configs = $bag->get('wilayah');
    }

    public function load(ObjectManager $manager) :void
    {
        if (!$this->configs['include_kelurahan']) {
            return;
        }

        $con = $this->em->getConnection();
        $kelurahans = $this->kelurahanSourceReader->read();
        $kecamatans = $this->kecamatanRepo->createQueryBuilder('t')
            ->select('t.id, t.code')
            ->getQuery()->getResult();
        foreach ($kecamatans as $kecamatan) {
            $kels = array_filter($kelurahans, function ($kecRow) use ($kecamatan) {

                return (preg_match("/^" . $kecamatan['code'] . "/i", $kecRow['kode']));
            });
            foreach ($kels as $kel) {
                $provId = (string)Uuid::v7();
                $con->insert('kelurahan', [
                    'id' => $provId,
                    'code' => $kel['kode'],
                    'name' => strtoupper($kel['nama']),
                    'kecamatan_id' => $kecamatan['id']
                ]);
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
