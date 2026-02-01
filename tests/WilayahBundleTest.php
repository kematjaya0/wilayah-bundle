<?php

namespace Kematjaya\WilayahBundle\Tests;

use Kematjaya\StateManagementBundle\Repository\StateLogRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @package Kematjaya\StateManagementBundle\Tests
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class WilayahBundleTest extends WebTestCase
{
    public static function getKernelClass():string
    {
        return AppKernelTest::class;
    }
    
    public function testInstanceContainer():void
    {
        $container = static::getContainer();
        $this->assertInstanceOf(ContainerInterface::class, $container);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->restoreExceptionHandler();
    }

    protected function restoreExceptionHandler(): void
    {
        while (true) {
            $previousHandler = set_exception_handler(static fn() => null);

            restore_exception_handler();

            if ($previousHandler === null) {
                break;
            }

            restore_exception_handler();
        }
    }
}
