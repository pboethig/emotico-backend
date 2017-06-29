<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 19:00
 */

namespace Mittax\MediaConverterBundle\Service\System;

use Mittax\MediaConverterBundle\Exception\InvalidConverterConfigException;
use Mittax\MediaConverterBundle\Exception\TempPathNotExistsException;
use Symfony\Component\Yaml\Yaml;

class Config
{
    /**
     * @var array
     */
    private static $_config = null;

    /**
     * @var array
     */
    private static $_rabbitMQConfig = null;

    /**
     * @var array
     */
    private static $_events = [];

    /**
     * @return mixed
     */
    public static function getMediaConverterConfig() : Array
    {
        $config = self::getBaseConfig();

        return $config['mittax_mediaconverter'];
    }

    /**
     * @return mixed
     */
    public static function getBaseConfig() : Array
    {
        if (self::$_config)
        {
            return self::$_config;
        }

        self::$_config = Yaml::parse(file_get_contents(__DIR__ . '/../../../../../app/config/mediaconverter.yml'));

        return self::$_config;
    }

    /**
     * @return array
     */
    public static function getConverters() : Array
    {
        return self::getMediaConverterConfig()['converters'];
    }

    /**
     * @param string $converter
     * @return string
     */
    public static function getFormatsByConverter(string $converter) : string
    {
        return self::getConverters()[$converter]['formats'];
    }

    /**
     * @param string $converterName
     * @return mixed
     */
    public static function getOutputFormatsByConverterName(string $converterName) : Array
    {
        if (!isset(Config::getConverters()[$converterName]['thumbnailOutputFormat']))
        {
            throw new InvalidConverterConfigException('No thumbnailconverter output options set for converter: ' . $converterName);
        }

        return Config::getConverters()[$converterName]['thumbnailOutputFormat'];
    }

    public static function getRootDir()
    {
        return __DIR__ . '/../../../../../..';
    }

    /**
     * @return array
     * @todo: replace whole bullshit config with one straight forward
     */
    public static function getPaths()
    {
        $paths = self::getMediaConverterConfig()['paths'];

        return $paths;
    }

    /**
     * @return string
     */
    public static function getPublicStorageUrl()
    {
        return self::getMediaConverterConfig()['paths']['storage']['public_url'];
    }

    /**
     * @return string
     */
    public static function getUploadPath()
    {
        return str_replace("\${root}", self::getRootDir(),self::getMediaConverterConfig()['paths']['storage']['upload']);
    }

    /**
     * @return string
     */
    public static function getExportPath()
    {
        return str_replace("\${root}", self::getRootDir(),self::getMediaConverterConfig()['paths']['storage']['export']);
    }

    /**
     * @return string
     */
    public static function getStoragePath()
    {
        return str_replace("\${root}", self::getRootDir(),self::getMediaConverterConfig()['paths']['storage']['path']);
    }

    /**
     * @return string
     */
    public static function getAssetsPath()
    {
        return str_replace("\${root}", self::getRootDir(),self::getMediaConverterConfig()['paths']['storage']['assets']);
    }

    /**
     * @return string
     */
    public static function getPublicWebUrl()
    {
        return self::getMediaConverterConfig()['paths']['public_web_url'];
    }

    /**
     * @return string
     */
    public static function getPublicWebSocketUrl()
    {
        return self::getMediaConverterConfig()['paths']['public_websocket_url'];
    }

    /**
     * @return mixed
     */
    public static function getTempPath()
    {
        $tempDir = self::getPaths()['temp'];

        if (!is_dir($tempDir))
        {
            throw new TempPathNotExistsException('Temp dir does not exist:' . $tempDir);
        }

        return $tempDir;
    }

    /**
     * @return mixed
     */
    public static function getInDesignServerShare()
    {
        $inDesignServerPathConfig = self::getPaths()['indesign_server'];

        return $inDesignServerPathConfig;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getSharedConfig()
    {
        $path = self::getStoragePath() . "/config/networkInfos.json";

        if(!file_get_contents($path)) throw new \Exception("shared configfile: " . $path . ' not found');

        $object = json_decode(file_get_contents($path));

        return $object;
    }

    /**
     * @return string
     */
    public static function getInDesignServerIp()
    {
        $networkInfos = self::getSharedConfig();

        return $networkInfos->InDesignServerIPAddress;
    }

    /**
     * @return array
     */
    public static function getExchangeConfiguration()
    {
        return self::getMediaConverterConfig()['exchangeConfiguration'];
    }

    /**
     * @return bool
     */
    public static function pingInDesignServer() : bool
    {
        $ip = self::getInDesignServerIp();

        $output = shell_exec('ping -c1 ' . $ip);

        $status = strpos($output, '1 received, 0% packet loss');

        if($status > -1) return true;

        return false;
    }

    /**
     * @return mixed
     */
    public static function getInDesignServerRoot()
    {
        return self::getInDesignServerShare()['root'];
    }

    /**
     * @return mixed
     */
    public static function getInDesignServerAssetPath()
    {
        return str_replace("\${root}", self::getInDesignServerRoot(),self::getInDesignServerShare()['assets']);
    }

    /**
     * @return mixed
     */
    public static function getInDesignServerWebhookClientUrls()
    {
        return self::getInDesignServerShare()['webhook_client_urls'];
    }

    /**
     * @return mixed
     */
    public static function getInDesignServerExportPath()
    {
        return str_replace("\${root}", self::getInDesignServerRoot(),self::getInDesignServerShare()['export']);
    }


    /**
     * @return array
     */
    public static function getRabbitMQConfig()
    {
        if (self::$_rabbitMQConfig)
        {
            return self::$_rabbitMQConfig['mittax_rabbit_mq'];
        }

        self::$_rabbitMQConfig = Yaml::parse(file_get_contents(__DIR__ . '/../../../../../app/config/rabbitmq.yml'));

        return self::$_rabbitMQConfig['mittax_rabbit_mq'];
    }

    /**
     * @return mixed
     */
    public static function getEvents(string $eventName) : array
    {
        if(isset(self::$_events[$eventName]))
        {
            return self::$_events[$eventName];
        }

        $eventConfig = Yaml::parse(file_get_contents(__DIR__ . '/../../../../../app/config/custom_events.yml'));

        if(isset($eventConfig['services'][$eventName]))
        {
            self::$_events[$eventName] = $eventConfig['services'][$eventName];

            return self::$_events[$eventName];
        }

        throw new \InvalidArgumentException("Event: " . $eventName . " not found");
    }

    /**
     * @return mixed
     */
    public static function getWebHook(string $eventName) : array
    {
        $webhookConfig = Yaml::parse(file_get_contents(__DIR__ . '/../../../../../app/config/webhooks.yml'));

        if(isset($webhookConfig['clients'][$eventName]))
        {
            return $webhookConfig['clients'][$eventName];
        }

        throw new \InvalidArgumentException("Webhook for Event: " . $eventName . " not found");
    }

    /**
     * @param string $eventName
     * @return string
     */
    public static function getEventListenerClass(string $eventName) : string {

        return self::getEvents($eventName)['class'];
    }

    /**
     * @param string $eventName
     * @return string
     */
    public static function getEventClass(string $eventName) : string
    {
        return self::getEvents($eventName)['tags'][0]['eventClassName'];
    }

    /**
     * @param string $eventName
     * @return string
     */
    public static function getEventListenerMethodName(string $eventName) : string
    {
        return self::getEvents($eventName)['tags'][0]['eventListenerMethodName'];
    }


}