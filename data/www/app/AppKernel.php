<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private static $_container;
 
    /**
     * override container to provide it global
     */
    public function getContainer()
    {
        if(!self::$_container)
        {
            self::$_container = $this->container;
        }

        return $this->container;
    }

    /**
     * @return mixed
     */
    public static function getContainerStatic()
    {
        return self::$_container;
    }
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Mittax\EmoticoBundle\MittaxEmoticoBundle(),
            new Mittax\CoreBundle\MittaxCoreBundle(),
            new Mittax\MessageBundle\MittaxMessageBundle(),
            new Mittax\WsseBundle\MittaxWsseBundle(),
            new Mittax\RabbitMQBundle\MittaxRabbitMQBundle(),
            new Mittax\MediaConverterBundle\MittaxMediaConverterBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Misd\PhoneNumberBundle\MisdPhoneNumberBundle(),
            new Gos\Bundle\WebSocketBundle\GosWebSocketBundle(),
            new Gos\Bundle\PubSubRouterBundle\GosPubSubRouterBundle(),
            new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }



}
