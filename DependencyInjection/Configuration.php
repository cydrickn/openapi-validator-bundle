<?php

declare(strict_types=1);

namespace Cydrickn\OpenApiValidatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('cydrickn_openapi_validator');
        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('validate_request')->defaultTrue()->end()
                ->booleanNode('validate_response')->defaultTrue()->end()
                ->arrayNode('schema')
                    ->children()
                        ->enumNode('factory')
                            ->values(['json-file', 'yaml-file'])
                            ->defaultValue('yaml-file')
                        ->end()
                        ->scalarNode('file')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}