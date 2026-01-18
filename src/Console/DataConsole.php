<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Kematjaya\WilayahBundle\Console;

use Kematjaya\WilayahBundle\Entity\Provinsi;
use Kematjaya\WilayahBundle\Entity\Kabupaten;
use Kematjaya\WilayahBundle\Entity\Kecamatan;
use Kematjaya\WilayahBundle\Entity\Desa;
use Kematjaya\WilayahBundle\SourceReader\VillageSourceReaderInterface;
use Kematjaya\WilayahBundle\SourceReader\DistrictSourceReaderInterface;
use Kematjaya\WilayahBundle\SourceReader\RegionSourceReaderInterface;
use Kematjaya\WilayahBundle\SourceReader\ProvinceSourceReaderInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'wilayah:insert'
)]
class DataConsole extends Command 
{
    private $data = ['provinsi', 'kabupaten', 'kecamatan', 'desa'];
    
    private $configs = [];
    
    public function __construct(private ParameterBagInterface $bag, private EntityManagerInterface $entityManager, private VillageSourceReaderInterface $villageSourceReader, private DistrictSourceReaderInterface $districtSourceReader, private RegionSourceReaderInterface $regionSourceReader, private ProvinceSourceReaderInterface $provinceSourceReader)
    {
        $configs = $bag->get('wilayah');
        $this->configs = $configs['filter'];
        parent::__construct();
    }
    
    protected function configure():void
    {
        $this
            ->addOption(
                    'data', 
                    null, 
                    InputOption::VALUE_IS_ARRAY|InputOption::VALUE_OPTIONAL,
                    'Which data will you insert ?',
                    $this->data
                );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
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
                
                $object = $this->entityManager->getRepository(Provinsi::class)->findOneBy([
                    'code' => $prov['id']
                ]); 
                if (null === $object) {
                    $object = new Provinsi();
                }

                $object->setCode($prov['id'])
                        ->setName(strtoupper($prov['nama']));

                $this->entityManager->persist($object);
                if (!in_array('kabupaten', $data)) {
                    continue;
                }

                $kabupatens = $this->regionSourceReader->filterByProvinceId($prov['id'], $this->configs['kabupaten']);
                foreach ($kabupatens as $kabupaten) {
                    $kabObject = $this->entityManager->getRepository(Kabupaten::class)->findOneBy([
                        'code' => $kabupaten['id'], 'provinsi' => $object
                    ]); 
                    if (null === $kabObject) {
                        $kabObject = new Kabupaten();
                        $kabObject->setProvinsi($object)
                            ->setCode($kabupaten['id']);
                    }
                    $kabObject
                            ->setName(strtoupper($kabupaten['nama']));

                    $this->entityManager->persist($kabObject);
                    if (!in_array('kecamatan', $data)) {
                        continue;
                    }

                    $kecamatans = $this->districtSourceReader->filterByRegionId($kabupaten['id'], $this->configs['kecamatan']);
                    foreach ($kecamatans as $kecamatan) {
                        $kecObject = $this->entityManager->getRepository(Kecamatan::class)->findOneBy([
                            'code' => $kecamatan['id'], 'kabupaten' => $kabObject
                        ]); 
                        if (null === $kecObject) {
                            $kecObject = new Kecamatan();
                            $kecObject->setKabupaten($kabObject)
                                ->setCode($kecamatan['id']);
                        }

                        $kecObject
                                ->setName(strtoupper($kecamatan['nama']));

                        $this->entityManager->persist($kecObject);
                        if (!in_array('desa', $data)) {
                            continue;
                        }

                        $villages = $this->villageSourceReader->filterByDistrictId($kecObject->getCode());
                        foreach ($villages as $village) {
                            $desaObject = $this->entityManager->getRepository(Desa::class)->findOneBy([
                                'code' => $kecamatan['id'], 'kecamatan' => $kecObject
                            ]); 
                            if (null === $desaObject) {
                                $desaObject = new Desa();
                                $desaObject->setKecamatan($kecObject)
                                    ->setCode($village['id']);
                            }

                            $desaObject
                                    ->setName(strtoupper($village['nama']));

                            $this->entityManager->persist($desaObject);
                        }
                    }
                }
            }

            $this->entityManager->flush();
            
        } catch (\Exception $ex) {
            $io->error($ex->getMessage());
            
            return self::FAILURE;
        }
        
        $io->info("success");
        
        return self::SUCCESS;
    }
}