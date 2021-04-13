<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('cydrickn_open_api_validator');
        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('validate_request')->defaultTrue()->end()
                ->booleanNode('validate_response')->defaultTrue()->end()
                ->arrayNode('schema')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('factory')
                            ->values(['json-file', 'json-dir', 'yaml-file', 'yaml-dir', 'php-file', 'php-dir', 'nelmio'])
                            ->defaultValue('yaml-file')
                        ->end()
                        ->scalarNode('file')->end()
                        ->scalarNode('dir')->end()
                    ->end()
                ->end()
                ->arrayNode('condition')
                    ->children()
                        ->arrayNode('query')
                            ->children()
                                ->scalarNode('name')->end()
                                ->scalarNode('value')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}