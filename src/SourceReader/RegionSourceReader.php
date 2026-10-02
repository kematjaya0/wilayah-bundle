<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Kematjaya\WilayahBundle\SourceReader;

/**
 * Description of RegionSourceReader
 *
 * @author guest
 */
class RegionSourceReader implements RegionSourceReaderInterface
{
    public function filterByProvinceId(string $provinceId, array $ids = []): array
    {
        $location = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Resources/data/v2/kota';

        $datas = json_decode(
            file_get_contents($location . DIRECTORY_SEPARATOR . $provinceId . '.json'),
            true
        );

        if (empty($ids)) {

            return $datas;
        }

        return array_filter($datas, fn(array $row): bool => in_array($row['id'], $ids));
    }
}
