<?php

namespace Kematjaya\WilayahBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('wilayah');
        $rootNode = $treeBuilder->getRootNode();

        $this->addRouteConfig($rootNode->children());

        return $treeBuilder;
    }

    public function addRouteConfig(NodeBuilder $node): void
    {
        $node
            ->booleanNode("auto-flush")
            ->defaultTrue()
            ->end()
            ->booleanNode("include_kelurahan")->defaultTrue()->end()
            ->arrayNode('filter')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('provinsi')->defaultValue([])->prototype('scalar')->end()->end()
            ->arrayNode('kabupaten')->defaultValue([])->prototype('scalar')->end()->end()
            ->arrayNode('kecamatan')->defaultValue([])->prototype('scalar')->end()->end()
            ->arrayNode('desa')->defaultValue([])->prototype('scalar')->end()->end()
            ->end();
    }

}
