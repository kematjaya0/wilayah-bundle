<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Kematjaya\WilayahBundle\SourceReader;

/**
 * Description of DistrictSourceReader
 *
 * @author guest
 */
class DistrictSourceReader implements DistrictSourceReaderInterface
{

    public function filterByRegionId(string $regionId, array $ids = []): array 
    {
        $location = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Resources/data/v2/kecamatan';
        
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
