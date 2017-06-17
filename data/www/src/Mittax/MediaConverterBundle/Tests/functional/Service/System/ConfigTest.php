<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 19:05
 */

namespace Mittax\MediaConverterBundle\Tests\Service\System;


use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ConfigTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testGetMediaConverterConfig()
    {
        $mediaConverterConfig = Config::getMediaConverterConfig();

        $this->assertNotEmpty($mediaConverterConfig);

        $this->assertNotEmpty($mediaConverterConfig['converters']);

        $this->assertNotEmpty($mediaConverterConfig['converters']['ffmpeg']);

        $this->assertNotEmpty($mediaConverterConfig['converters']['imagine']);

        $this->assertNotEmpty(Config::getConverters());

        $this->assertNotEmpty(Config::getConverters()['ffmpeg']);

        $this->assertNotEmpty(Config::getFormatsByConverter('ffmpeg'));
    }

    public function testGetOutputFormatsByConverterName()
    {
        $ImagineOutFormats = Config::getOutputFormatsByConverterName('imagine');

        $this->assertNotEmpty($ImagineOutFormats);
    }

    public function testGetPaths()
    {
        $paths = Config::getPaths();

        $this->assertNotEmpty($paths);

        $this->assertNotEmpty($paths['temp']);

        $this->assertNotEmpty($paths['storage']);

        $this->assertNotEmpty($paths['storage']['path']);

        $this->assertNotNull(Config::getMediaConverterConfig()['functionaltests']['executeThumbnailGeneration']);

        $this->assertNotNull(Config::getTempPath());
    }

    public function testExchange()
    {
        $exchange = Config::getExchangeConfiguration();

        $this->assertNotEmpty($exchange);

        $this->assertNotEmpty($exchange['producers']);

        $this->assertNotEmpty($exchange['producers']['thumbnails']);

        $this->assertNotEmpty($exchange['producers']['thumbnails']['imagine']['name']);

        $this->assertNotEmpty($exchange['producers']['thumbnails']['imagine']['queueName']);

        $this->assertNotEmpty($exchange['producers']['thumbnails']['imagine']['routingKey']);

        $this->assertNotEmpty($exchange['producers']['thumbnails']['imagine']['type']);

        $this->assertNotNull($exchange['producers']['thumbnails']['imagine']['auto_delete']);

        $this->assertNotEmpty($exchange['producers']['thumbnails']['imagine']['durable']);

        $this->assertNotNull($exchange['producers']['thumbnails']['imagine']['passive']);

        $this->assertNotNull($exchange['producers']['thumbnails']['imagine']['parameters']);

        $this->assertNotNull($exchange['producers']['thumbnails']['imagine']['nowait']);

        $this->assertNotNull($exchange['producers']['thumbnails']['imagine']['exclusive']);
    }


    public function testRabbitMQConfig()
    {
        $rabbitMQConfig = Config::getRabbitMQConfig();

        $this->assertNotNull($rabbitMQConfig);

        $this->assertNotEmpty($rabbitMQConfig['connections']);
    }


    public function testInDesignServerConfig()
    {
        $indesignServerConfig = Config::getInDesignServerShare();

        $this->assertNotNull($indesignServerConfig['root']);

        $this->assertNotNull($indesignServerConfig['export']);

        $this->assertNotNull($indesignServerConfig['assets']);

        $this->assertNotNull($indesignServerConfig['webhook_client_urls'][0]);

        $this->assertNotEmpty(Config::getInDesignServerAssetPath());

        $this->assertNotEmpty(Config::getInDesignServerExportPath());

        $this->assertNotEmpty(Config::getInDesignServerRoot());

        $this->assertFalse(strpos(Config::getInDesignServerExportPath(), "\${root}") >-1);

        $this->assertFalse(strpos(Config::getInDesignServerAssetPath(), "\${root}") >-1);
    }


    public function testGetEvents()
    {
        $eventName = 'indesignserver.lowres.created';

        $events = Config::getEvents($eventName);

        $this->assertNotEmpty($events);

        $this->assertNotEmpty($events['class']);

        $this->assertNotEmpty(Config::getEventListenerClass($eventName));

        $this->assertNotEmpty(Config::getEventClass($eventName));

        $this->assertNotEmpty(Config::getEventListenerMethodName($eventName));

        $this->assertEquals('onIndesignServerLowresCreated', Config::getEventListenerMethodName($eventName));
    }

    public function testPublicWebUrl()
    {
        $url = Config::getPublicWebUrl();

        $this->assertNotEmpty($url);
    }

    public function testGetUploadPath()
    {
        $url = Config::getUploadPath();

        $this->assertNotEmpty($url);
    }

    public function testPublicWebSocketUrl()
    {
        $url = Config::getPublicWebSocketUrl();

        $this->assertNotEmpty($url);
    }

    public function testPublicStorageUrl()
    {
        $url = Config::getPublicStorageUrl();

        $this->assertNotEmpty($url);
    }

    public function testGetExportPath()
    {
        $path = Config::getExportPath();

        $this->assertNotEmpty($path);
    }

    public function testGetInDesignServerIp()
    {
        $ip = Config::getInDesignServerIp();

        $this->assertNotEmpty($ip);
    }

    public function testGetWebHook()
    {
        $this->assertNotEmpty(Config::getWebHook('thumbnail.finedata.created'));

        $this->assertTrue(strpos(Config::getWebHook('thumbnail.finedata.created')[0], '://') > -1);
    }

    public function testPingInDesignServer()
    {
        $this->assertTrue(Config::pingInDesignServer());
    }

    public function testGetStoragePath()
    {
        $this->assertNotEmpty(Config::getStoragePath());
    }
}