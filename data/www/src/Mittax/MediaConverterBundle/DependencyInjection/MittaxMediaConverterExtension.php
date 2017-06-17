<?php

namespace Mittax\MediaConverterBundle\DependencyInjection;

use Mittax\MediaConverterBundle\Exception\MediaConverterConfigNotFoundException;
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
class MittaxMediaConverterExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @param ContainerBuilder $container
     * @throws MediaConverterConfigNotFoundException
     */
    public function prepend(ContainerBuilder $container)
    {
        /**
         * Get the parameters from app/config/parameters
         */
        $config = Yaml::parse(file_get_contents(__DIR__ . '/../../../../app/config/mediaconverter.yml'));

        if (!isset($config['mittax_mediaconverter']))
        {
            throw new MediaConverterConfigNotFoundException('No mittax_mediaconverter configuration found in app/config/parameters.yml. Forgot to add it?. @see documentation');
        }

        $container->setParameter('mittax_mediaconverter.profilers', $config['mittax_mediaconverter']['profilers']);
        $container->setParameter('mittax_mediaconverter.converters', $config['mittax_mediaconverter']['converters']);
        $container->prependExtensionConfig('mittax_mediaconverter', $config['mittax_mediaconverter']);
    }
}
