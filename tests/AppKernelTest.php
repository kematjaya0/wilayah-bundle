<?php

namespace Kematjaya\WilayahBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Kematjaya\WilayahBundle\WilayahBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class AppKernelTest extends Kernel
{
    public function registerBundles():iterable
    {
        return [
            new WilayahBundle(),
            new FrameworkBundle(),
            new DoctrineBundle()
        ];
    }
    
    public function registerContainerConfiguration(LoaderInterface $loader):void
    {
        $loader->load(function (ContainerBuilder $container) use ($loader) {
            $loader->load(__DIR__ . DIRECTORY_SEPARATOR . 'config/config.yml');
            $loader->load(__DIR__ . DIRECTORY_SEPARATOR . 'config/services.yaml');
            
            $container->addObjectResource($this);
        });
    }
}
