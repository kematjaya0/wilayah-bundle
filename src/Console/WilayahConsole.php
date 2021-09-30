<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Kematjaya\WilayahBundle\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Description of WilayahConsole
 *
 * @author guest
 */
class WilayahConsole extends Command
{
    protected static $defaultName = 'wilayah:download';
    
    /**
     * 
     * @var HttpClientInterface
     */
    private $httpClient;
    
    public function __construct(mixed $name = null, HttpClientInterface $httpClient) 
    {
        $this->httpClient = $httpClient;
        parent::__construct($name);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $response = $this->httpClient->request(Request::METHOD_GET, 'https://dev.farizdotid.com/api/daerahindonesia/provinsi');
            $data = $response->toArray();
            $filePath = dirname(__DIR__) . '/../resources/wilayah/provinsi.json';
            $fileSystem = new Filesystem();
            if (!$fileSystem->exists($filePath)) {
                $fileSystem->dumpFile($filePath, json_encode([]));
            }
            
            $fileSystem->dumpFile($filePath, json_encode($data['provinsi']));
            
            foreach ($data['provinsi'] as $provinsi) {
                $this->getKota($provinsi);
            }
        } catch (\Exception $ex) {
            return [
                'error' => $ex->getMessage()
            ];
        }
        
        return self::SUCCESS;
    }
    
    protected function getKota(array $provinsi)
    {
        $response = $this->httpClient->request(Request::METHOD_GET, 'https://dev.farizdotid.com/api/daerahindonesia/kota?id_provinsi=' . $provinsi['id']);
        $data = $response->toArray();
        $filePath = dirname(__DIR__) . sprintf('/../resources/wilayah/kota/%s.json', $provinsi['id']);
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($filePath)) {
            $fileSystem->dumpFile($filePath, json_encode([]));
        }

        $fileSystem->dumpFile($filePath, json_encode($data['kota_kabupaten']));

        foreach ($data['kota_kabupaten'] as $kabupaten) {
            $this->getKecamatan($kabupaten);
        }
    }
    
    protected function getKecamatan(array $kabupaten)
    {
        $response = $this->httpClient->request(Request::METHOD_GET, 'https://dev.farizdotid.com/api/daerahindonesia/kecamatan?id_kota=' . $kabupaten['id']);
        $data = $response->toArray();
        $filePath = dirname(__DIR__) . sprintf('/../resources/wilayah/kecamatan/%s.json', $kabupaten['id']);
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($filePath)) {
            $fileSystem->dumpFile($filePath, json_encode([]));
        }

        $fileSystem->dumpFile($filePath, json_encode($data['kecamatan']));

        foreach ($data['kecamatan'] as $kecamatan) {
            $this->getKelurahan($kecamatan);
        }
    }
    
    protected function getKelurahan(array $kecamatan)
    {
        $response = $this->httpClient->request(Request::METHOD_GET, 'https://dev.farizdotid.com/api/daerahindonesia/kelurahan?id_kecamatan=' . $kecamatan['id']);
        $data = $response->toArray();
        $filePath = dirname(__DIR__) . sprintf('/../resources/wilayah/kelurahan/%s.json', $kecamatan['id']);
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($filePath)) {
            $fileSystem->dumpFile($filePath, json_encode([]));
        }

        $fileSystem->dumpFile($filePath, json_encode($data['kelurahan']));
    }
}
