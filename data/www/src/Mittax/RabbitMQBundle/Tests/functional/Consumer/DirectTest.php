<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 10.12.16
 * Time: 22:13
 */

namespace Mittax\RabbitMQBundle\Test\Consumer;

use Mittax\RabbitMQBundle\Exception\Handler\Consumer as ConsumerExceptionHandler;
use Mittax\RabbitMQBundle\Service\Consumer\Type\Direct;
use \Mittax\RabbitMQBundle\Tests\RequestAbstract;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class DirectTest
 * @package Mittax\RabbitMQBundle\Test\Consumer
 */
class DirectTest extends RequestAbstract
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testConfirmRequestBatchExecutionLazySocketConnection()
    {
        $ConfirmConsumeRequest = new Direct($this->getRequestFixure('default'));

        try
        {
            $ConfirmConsumeRequest->execute();

        }catch (Exception $e)
        {
            new ConsumerExceptionHandler($e);
        }

        $this->assertNotNull($ConfirmConsumeRequest);
    }
}