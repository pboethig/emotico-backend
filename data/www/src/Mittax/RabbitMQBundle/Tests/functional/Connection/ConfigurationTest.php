<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.12.16
 * Time: 14:06
 */

namespace Mittax\RabbitMQBundle\Tests\Connection;


use Mittax\RabbitMQBundle\Service\Connection\Configuration\Configuration;
use Mittax\RabbitMQBundle\Service\Connection\Configuration\Factory;
use Mittax\RabbitMQBundle\Tests\AbstractKernelTestCase;

class ConfigurationTest extends AbstractKernelTestCase
{

    /**
     * @var Configuration
     */
    protected $_configuration;

    public function setUp()
    {
        parent::setUp();
    }

    public function testConfigurationFactory()
    {
        $this->assertInstanceOf(Factory::class, $this->_configurationFactory);
    }

    public function testConfigurationExists()
    {
        $this->assertInstanceOf(Configuration::class,$this->_configurationFactory->getDefaultConnection());

        $this->assertNotEmpty($this->_configurationFactory->getDefaultConnection());
    }

    public function testDefaultConfiguration()
    {
        $configuration = $this->_configurationFactory->getDefaultConnection();

        $this->assertInstanceOf(Configuration::class, $configuration);

        $this->assertNotEmpty($configuration->getName());

        $this->assertNotEmpty($configuration->getHost());

        $this->assertNotEmpty($configuration->getConnectionTimeout());

        $this->assertNotNull($configuration->getHartBeat());

        $this->assertNotEmpty($configuration->getPassword());

        $this->assertNotEmpty($configuration->getPort());

        $this->assertNotEmpty($configuration->getRawData());

        $this->assertNotEmpty($configuration->getSslContext());

        $this->assertNotEmpty($configuration->getUsername());

        $this->assertNotEmpty($configuration->getVhost());

        $this->assertNotNull($configuration->isDebug());

        $this->assertNotNull($configuration->isKeepAlive());

        $this->assertNotNull($configuration->isLazy());

        $this->assertNotNull($configuration->isUseSockets());

        $this->assertNotNull($configuration->getLocale());

        $this->assertNotNull($configuration->getLoginMethod());

        $this->assertNotNull($configuration->isInsist());
    }

    /**
     * @expectedException \Mittax\RabbitMQBundle\Exception\SSLCAFilePathDoesNotExistsException
     */
    public function testSSLOptionsArraySSLPathDoesNotExists()
    {
        $configuration = $this->_configurationFactory->getDefaultConnection();

        $configuration->setSslCafilepath('asdasdasda');

        $configuration->setSslLocalcertpath('sdasdsad');

        $configuration->setSslVerifyPeer(true);

        $this->assertTrue(is_array($configuration->getSSLOptionArray()));
    }

    /**
     * @expectedException \Mittax\RabbitMQBundle\Exception\LocalCertPathDoesNotExistsException
     */
    public function testSSLOptionsArrayLocalCertPathDoesNotExists()
    {
        $configuration = $this->_configurationFactory->getDefaultConnection();

        $configuration->setSslLocalcertpath('asdasdasd');

        $configuration->setSslCafilepath(__FILE__);

        $configuration->setSslVerifyPeer(true);

        $this->assertTrue(is_array($configuration->getSSLOptionArray()));
    }


    public function testSSLOptionsArray()
    {
        $configuration = $this->_configurationFactory->getDefaultConnection();

        $configuration->setSslLocalcertpath(__FILE__);

        $configuration->setSslCafilepath(__FILE__);

        $configuration->setSslVerifyPeer(true);

        $sslOptions = $configuration->getSSLOptionArray();

        $expected = [
            'cafile' => __FILE__,
            'local_cert' => __FILE__,
            'verify_peer' => true
        ];

        $this->assertEquals($expected, $sslOptions);
    }


    public function testGetConfigurationByName()
    {
        $this->assertNotEmpty($this->_configurationFactory->getConnectionByName('default'));
    }

    public function testGetConfigurationCollection()
    {
        $this->assertNotEmpty($this->_configurationFactory->getCollection());
    }
}