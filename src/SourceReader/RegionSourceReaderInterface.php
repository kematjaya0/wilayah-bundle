<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Kematjaya\WilayahBundle\SourceReader;

/**
 * Description of RegionSourceReaderInterface
 *
 * @author guest
 */
interface RegionSourceReaderInterface
{
    public function filterByProvinceId(string $provinceId, array $ids = []): array;
}
