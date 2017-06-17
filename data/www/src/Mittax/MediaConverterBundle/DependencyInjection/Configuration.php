<?php

namespace Mittax\MediaConverterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mittax_rabbit_mq');

        $this->addConnections($rootNode);

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    protected function addConnections(ArrayNodeDefinition $node)
    {
        $node
            ->children()
            ->arrayNode('converters')
            ->useAttributeAsKey('key')
            ->canBeUnset()
            ->prototype('array')
            ->children()
                ->scalarNode('version')->defaultValue('')->end()
                ->scalarNode('executable')->defaultValue('')->end()
                ->scalarNode('formats')->end()
                ->scalarNode('thumbnailConverterClassName')->end()
            ->end()
            ->end()
            ->end()

            /**
             * paths
             */
            ->arrayNode('paths')
            ->useAttributeAsKey('key')
            ->canBeUnset()
            ->prototype('array')
            ->children()
            ->scalarNode('path')->defaultValue('')->end()
            ->end()
            ->end()
            ->end()
            /**
             * Consumers
             */
            ->arrayNode('consumers')
            ->useAttributeAsKey('key')
            ->canBeUnset()
            ->prototype('array')
            ->children()
                ->scalarNode('queue')->defaultValue('')->end()
                ->scalarNode('consumer_tag')->defaultValue('')->end()
                ->booleanNode('no_ack')->end()
                ->booleanNode('no_local')->end()
                ->booleanNode('exclusive')->end()
                ->booleanNode('nowait')->end()
            ->end()
            ->end()
            ->end()
            /**
             * Profilers
             */
            ->arrayNode('profilers')
            ->useAttributeAsKey('key')
            ->canBeUnset()
            ->prototype('array')
            ->children()
            ->scalarNode('clientId')->defaultValue('')->end()
            ->scalarNode('clientToken')->defaultValue('')->end()
            ->end()
            ->end()
            ->end()
            /**
             * End Consumers
             */
            //end first child
            ->end()
        ;
    }
}
