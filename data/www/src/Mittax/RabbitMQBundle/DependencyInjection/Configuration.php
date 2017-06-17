<?php

namespace Mittax\RabbitMQBundle\DependencyInjection;

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
            ->arrayNode('connections')
            ->useAttributeAsKey('key')
            ->canBeUnset()
            ->prototype('array')
            ->children()
                ->scalarNode('url')->defaultValue('')->end()
                ->scalarNode('host')->defaultValue('localhost')->end()
                ->scalarNode('port')->defaultValue(5672)->end()
                ->scalarNode('apiport')->defaultValue(15672)->end()
                ->scalarNode('ssl_cafilepath')->end()
                ->scalarNode('ssl_localcertpath')->end()
                ->booleanNode('ssl_verify_peer')->defaultValue(false)->end()
                ->booleanNode('insist')->defaultValue(false)->end()
                ->scalarNode('login_method')->defaultValue('AMQPLAIN')->end()
                ->booleanNode('login_response')->defaultValue(false)->end()
                ->scalarNode('locale')->defaultValue('en_US')->end()
                ->scalarNode('username')->defaultValue('guest')->end()
                ->scalarNode('vhost')->defaultValue('/')->end()
                ->scalarNode('debug')->defaultValue('true')->end()
                ->scalarNode('password')->defaultValue('guest')->end()
                ->booleanNode('lazy')->defaultFalse()->end()
                ->scalarNode('connection_timeout')->defaultValue(3)->end()
                ->scalarNode('read_write_timeout')->defaultValue(3)->end()
                ->booleanNode('use_socket')->defaultValue(false)->end()
                ->arrayNode('ssl_context')
                    ->useAttributeAsKey('key')
                    ->canBeUnset()
                    ->prototype('variable')->end()
                ->end()
                ->booleanNode('keepalive')->defaultFalse()->info('requires php-amqplib v2.4.1+ and PHP5.4+')->end()
                ->scalarNode('heartbeat')->defaultValue(0)->info('requires php-amqplib v2.4.1+')->end()
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
             * End Consumers
             */
            //end first child
            ->end()
        ;
    }
}
