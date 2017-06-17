<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 20.11.16
 * Time: 13:04
 */

namespace Mittax\MessageBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

require_once __DIR__ . '/../../../../app/autoload.php';
require_once __DIR__ . '/../../../../app/bootstrap.php.cache';
require_once __DIR__ . '/../../../../app/AppKernel.php';

class AbstractKernelTestCase extends KernelTestCase
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var KernelInterface
     */
    protected $_kernel;

    
    public function setUp()
    {
        $this->_kernel = static::createKernel();
        $this->_kernel->boot();

        $this->container = $this->_kernel->getContainer();
    }
}