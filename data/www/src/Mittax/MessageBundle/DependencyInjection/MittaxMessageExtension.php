<?php

namespace Mittax\MessageBundle\DependencyInjection;

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
class MittaxMessageExtension extends Extension implements PrependExtensionInterface
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
     * Prepend needed configparams to the loading process.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $config = Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/config.yml'));

        foreach ($config as $key => $configuration) {

            $container->setParameter('mittax.message.twillo_sid', $configuration['twillo_sid']);
            $container->prependExtensionConfig($key, $configuration);

            $container->setParameter('mittax.message.twillo_authtoken', $configuration['twillo_authtoken']);
            $container->prependExtensionConfig($key, $configuration);

            $container->setParameter('mittax.message.twillo_number', $configuration['twillo_number']);
            $container->prependExtensionConfig($key, $configuration);
        }
    }
}
