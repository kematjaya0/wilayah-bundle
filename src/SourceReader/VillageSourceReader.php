<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Kematjaya\WilayahBundle\SourceReader;

/**
 * Description of VillageSourceReader
 *
 * @author guest
 */
class VillageSourceReader implements VillageSourceReaderInterface
{
    public function filterByDistrictId(string $regionId, array $ids = []): array
    {
        $location = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Resources/data/v2/kelurahan';
        
        $datas = json_decode(
            file_get_contents($location . DIRECTORY_SEPARATOR . $regionId . '.json'), true
        );
        
        if (empty($ids)) {
            
            return $datas;
        }
        
        return array_filter($datas, function ($row) use ($ids) {
                
            return in_array($row['id'], $ids);
        });
    }

}
