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
    //put your code here
    public function read(): array 
    {
        $location = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Resources/data';
        
        return json_decode(
            file_get_contents($location . DIRECTORY_SEPARATOR . 'kecamatan.json'), true
        );
    }

}
