<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 10:31
 */

namespace Mittax\MediaConverterBundle\Repository\Storage;

use Mittax\MediaConverterBundle\Service\Storage\IResolver;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FilesystemResolver
 * @package Mittax\MediaConverterBundle\Repository\Storage
 */
class FilesystemResolverFactory
{
    /**
     * @var array
     */
    private static $_mediaConverterConfig = null;

    /**
     * FilesystemResolverFactory constructor.
     * @param array|null $filesystemConfig - can be used for unittesting
     */
    public function __construct()
    {
        if(!(self::$_mediaConverterConfig))
        {
            self::$_mediaConverterConfig = Yaml::parse(file_get_contents(__DIR__ . '/../../../../../app/config/mediaconverter.yml'));
        }
    }

    /**
     * @return IResolver
     */
    public function getResolver() : IResolver
    {
        $resolverClassName = self::$_mediaConverterConfig['mittax_mediaconverter']['paths']['storage']['resolver'];

        /** @var  $resolver IResolver */
        $resolver = new $resolverClassName(self::$_mediaConverterConfig['mittax_mediaconverter']['paths']['storage']);

        return $resolver;
    }

    /**
     * @return array
     */
    public static function getMediaConverterConfig()
    {
        return self::$_mediaConverterConfig;
    }
}