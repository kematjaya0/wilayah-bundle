<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace Kematjaya\WilayahBundle\SourceReader;

/**
 *
 * @author guest
 */
interface DistrictSourceReaderInterface
{
    public function filterByRegionId(string $regionId, array $ids = []): array;
}
