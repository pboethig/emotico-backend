<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 20.11.16
 * Time: 13:04
 */

namespace Mittax\RabbitMQBundle\Tests;

use Mittax\RabbitMQBundle\Service\Consumer\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

require_once __DIR__ . '/../../../../app/autoload.php';
require_once __DIR__ . '/../../../../app/bootstrap.php.cache';
require_once __DIR__ . '/../../../../app/AppKernel.php';

class AbstractKernelTestCase extends KernelTestCase
{

    /**
     * @var Factory
     */
    protected $_consumerFactory;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \Mittax\RabbitMQBundle\Service\Connection\Configuration\Factory
     */
    protected $_configurationFactory;

    /**
     * @var \Mittax\RabbitMQBundle\Service\Connection\Factory
     */
    protected $_connectionFactory;

    /**
     * @var KernelInterface
     */
    protected $_kernel;

    
    public function setUp()
    {
        $this->_kernel = static::createKernel();
        $this->_kernel->boot();

        $this->container = $this->_kernel->getContainer();

        $this->_configurationFactory = $this->container->get('mittax_rabbitmq.service.connection.configuration.factory');

        $this->_connectionFactory = $this->container->get('mittax_rabbitmq.service.connection.factory');

        $this->_consumerFactory = $this->container->get('mittax_rabbitmq.service.consumer.factory');
    }
}