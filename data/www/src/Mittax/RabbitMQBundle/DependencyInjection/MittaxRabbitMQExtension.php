<?php

namespace Mittax\RabbitMQBundle\DependencyInjection;

use Mittax\RabbitMQBundle\Exception\RabbitMQConfigNotFoundException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class MittaxRabbitMQExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @param ContainerBuilder $container
     * @throws RabbitMQConfigNotFoundException
     */
    public function prepend(ContainerBuilder $container)
    {
        /**
         * Get the parameters from app/config/parameters
         */
        $config = Yaml::parse(file_get_contents(__DIR__ . '/../../../../app/config/rabbitmq.yml'));

        if (!isset($config['mittax_rabbit_mq']))
        {
            throw new RabbitMQConfigNotFoundException('No mittax_rabbitmq configuration found in app/config/parameters.yml. Forgot to add it?. @see documentation');
        }

        $container->setParameter('mittax_rabbit_mq.connections', $config['mittax_rabbit_mq']['connections']);
        $container->prependExtensionConfig('mittax_rabbit_mq', $config['mittax_rabbit_mq']);

        $container->setParameter('mittax_rabbit_mq.consumers', $config['mittax_rabbit_mq']['consumers']);
    }
}
