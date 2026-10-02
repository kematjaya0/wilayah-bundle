<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Kematjaya\WilayahBundle\Console;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Kematjaya\WilayahBundle\Entity\Desa;
use Kematjaya\WilayahBundle\Entity\Kabupaten;
use Kematjaya\WilayahBundle\Entity\Kecamatan;
use Kematjaya\WilayahBundle\Entity\Provinsi;
use Kematjaya\WilayahBundle\SourceReader\DistrictSourceReaderInterface;
use Kematjaya\WilayahBundle\SourceReader\ProvinceSourceReaderInterface;
use Kematjaya\WilayahBundle\SourceReader\RegionSourceReaderInterface;
use Kematjaya\WilayahBundle\SourceReader\VillageSourceReaderInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'wilayah:insert'
)]
class DataConsole extends Command
{
    private array $data = ['provinsi', 'kabupaten', 'kecamatan', 'desa'];

    private readonly array $configs;
    private readonly bool $autoFlush;

    public function __construct(
        ParameterBagInterface $bag,
        private readonly EntityManagerInterface $entityManager,
        private readonly VillageSourceReaderInterface $villageSourceReader,
        private readonly DistrictSourceReaderInterface $districtSourceReader,
        private readonly RegionSourceReaderInterface $regionSourceReader,
        private readonly ProvinceSourceReaderInterface $provinceSourceReader,
    ) {
        $configs = $bag->get('wilayah');
        $this->configs = $configs['filter'];
        $this->autoFlush = (bool) ($configs['auto-flush'] ?? true);
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'data',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Which data will you insert ?',
                $this->data
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '2048M');
        $io = new SymfonyStyle($input, $output);
        $con = $this->entityManager->getConnection();
        try {
            $data = array_map(function ($row) {
                if (!in_array($row, $this->data)) {
                    throw new \Exception(
                        sprintf("data '%s' not available from (%s)'", $row, implode(", ", $this->data))
                    );
                }

                return $row;
            }, $input->getOption('data', []));

            $provinsis = $this->provinceSourceReader->findAll(
                $this->configs['provinsi']
            );
            foreach ($provinsis as $prov) {

                $io->info(
                    sprintf("processing: '%s'", strtoupper($prov['nama']))
                );

                try {
                    $row = $this->entityManager->getRepository(Provinsi::class)
                        ->createQueryBuilder('t')
                        ->select('t.id')
                        ->where('t.code = :c')->setParameter('c', $prov['id'])
                        ->getQuery()->getSingleResult();
                    $provId = $row['id'];
                    $con->update('provinsi', [
                        'code' => $prov['id'],
                        'name' => strtoupper($prov['nama']),
                    ], ['id' => $provId]);
                } catch (NoResultException) {
                    $provId = (string) Uuid::v7();
                    $con->insert('provinsi', [
                        'id' => $provId,
                        'code' => $prov['id'],
                        'name' => strtoupper($prov['nama']),
                    ]);
                }

                if (!in_array('kabupaten', $data)) {
                    continue;
                }

                $kabupatens = $this->regionSourceReader->filterByProvinceId($prov['id'], $this->configs['kabupaten']);
                foreach ($kabupatens as $kabupaten) {
                    try {
                        $row = $this->entityManager->getRepository(Kabupaten::class)
                            ->createQueryBuilder('t')->leftJoin('t.provinsi', 'p')
                            ->select('t.id')
                            ->where('t.code = :c')->setParameter('c', $kabupaten['id'])
                            ->andWhere('p.id = :pp')->setParameter('pp', $provId)
                            ->getQuery()->getSingleResult();
                        $kabId = $row['id'];
                        $con->update('kabupaten', [
                            'code' => $kabupaten['id'],
                            'name' => strtoupper($kabupaten['nama']),
                            'provinsi_id' => $provId,
                        ], ['id' => $kabId]);
                    } catch (NoResultException) {
                        $kabId = (string) Uuid::v7();
                        $con->insert('kabupaten', [
                            'id' => $kabId,
                            'code' => $kabupaten['id'],
                            'provinsi_id' => $provId,
                            'name' => strtoupper($kabupaten['nama']),
                        ]);
                    }

                    if (!in_array('kecamatan', $data)) {
                        continue;
                    }

                    $kecamatans = $this->districtSourceReader->filterByRegionId($kabupaten['id'], $this->configs['kecamatan']);
                    $io->title(sprintf("kabupaten %s, total %s kecamatan", $kabupaten['nama'], count($kecamatans)));
                    foreach ($kecamatans as $kecamatan) {
                        try {
                            $row = $this->entityManager->getRepository(Kecamatan::class)
                                ->createQueryBuilder('t')->leftJoin('t.kabupaten', 'p')
                                ->select('t.id')
                                ->where('t.code = :c')->setParameter('c', $kecamatan['id'])
                                ->andWhere('p.id = :pp')->setParameter('pp', $kabId)
                                ->getQuery()->getSingleResult();
                            $kecId = $row['id'];
                            $con->update('kecamatan', [
                                'code' => $kecamatan['id'],
                                'name' => strtoupper($kecamatan['nama']),
                                'kabupaten_id' => $kabId,
                            ], ['id' => $kecId]);
                        } catch (NoResultException) {
                            $kecId = (string) Uuid::v7();
                            $con->insert('kecamatan', [
                                'id' => $kecId,
                                'code' => $kecamatan['id'],
                                'kabupaten_id' => $kabId,
                                'name' => strtoupper($kecamatan['nama']),
                            ]);
                        }

                        if (!in_array('desa', $data)) {
                            continue;
                        }

                        $villages = $this->villageSourceReader->filterByDistrictId($kecamatan['id']);
                        foreach ($villages as $village) {
                            try {
                                $row = $this->entityManager->getRepository(Desa::class)
                                    ->createQueryBuilder('t')->leftJoin('t.kecamatan', 'p')
                                    ->select('t.id')
                                    ->where('t.code = :c')->setParameter('c', $village['id'])
                                    ->andWhere('p.id = :pp')->setParameter('pp', $kecId)
                                    ->getQuery()->getSingleResult();
                                $desaId = $row['id'];
                                $con->update('desa', [
                                    'code' => $village['id'],
                                    'name' => strtoupper($village['nama']),
                                    'kecamatan_id' => $kecId,
                                ], ['id' => $desaId]);
                            } catch (NoResultException) {
                                $desaId = (string) Uuid::v7();
                                $con->insert('desa', [
                                    'id' => $desaId,
                                    'code' => $village['id'],
                                    'kecamatan_id' => $kecId,
                                    'name' => strtoupper($village['nama']),
                                ]);
                            }
                        }
                    }
                }
            }

            $this->entityManager->flush();
            $this->entityManager->clear();
        } catch (\Exception $ex) {
            $io->error(sprintf("error: %s", $ex->getMessage()));

            return self::FAILURE;
        }

        $io->info("success");

        return self::SUCCESS;
    }
}
