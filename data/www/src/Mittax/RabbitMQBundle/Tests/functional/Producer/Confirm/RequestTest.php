<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 04.12.16
 * Time: 18:27
 */

namespace Mittax\RabbitMQBundle\Tests\Producer\Confirm;

use Mittax\RabbitMQBundle\Service\Connection\IConnection;
use Mittax\RabbitMQBundle\Service\Exchange\Configuration;
use Mittax\RabbitMQBundle\Service\Producer\Confirm;
use Mittax\RabbitMQBundle\Service\Producer\Confirm\Request;
use Mittax\RabbitMQBundle\Tests\RequestAbstract;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class ConfigurationTest
 * @package Mittax\RabbitMQBundle\Tests\Service\Exchange
 */
class RequestTest extends RequestAbstract
{

    public function setUp()
    {
        parent::setUp();
    }


    public function testRequestInstance()
    {
        $this->assertInstanceOf(Request::class, $this->_request);
    }

    public function testTypeInstance()
    {
        $type = new Configuration($this->_exchangeDefinition);

        $this->assertInstanceOf(Configuration::class, $type);
    }

    public function testBuildRequest()
    {
        $request = $this->getRequestFixure('default');

        $this->assertNotEmpty($request);
    }

    public function testRequestMessages()
    {
        $request = $this->getRequestFixure('default');

        $this->assertNotEmpty($request->getMessages());

        $this->assertEquals(1000, count($request->getMessages()));

        $this->assertInstanceOf(AMQPMessage::class, $request->getMessages()[0]);
    }

    public function testGetAcknowledgementCallback()
    {
        $request = $this->getRequestFixure('default');

        $callBack = $request->getAcknowledgementCallback();
        $this->assertTrue(is_callable($callBack));

        $response = $callBack(new AMQPMessage('a messagebody', []));
        $this->assertContains($response, 'Message acked with content a messagebody');
    }

    public function testGetNacknowledgementCallback()
    {
        $request = $this->getRequestFixure('default');

        $callBack = $request->getNacknowledgementCallback();
        $this->assertTrue(is_callable($callBack));


        $response = $callBack(new AMQPMessage('a messagebody', []));
        $this->assertContains($response, 'Message not acked with content a messagebody');
    }

    public function testGetReturnListener()
    {
        $request = $this->getRequestFixure('default');

        $callBack = $request->getReturnListener();
        $this->assertTrue(is_callable($callBack));


        $response = $callBack('replyCode', 'replyText','testExchange','routingCode', new AMQPMessage('a messagebody', []));
        $this->assertContains('Message returned with content a messagebody', $response);
    }

    public function testConnectionInterface()
    {
        $this->assertInstanceOf(IConnection::class, $this->getRequestFixure('default')->getConnectionInterface());
    }

    public function testGetDeclareConfig()
    {
        $this->assertInstanceOf(Configuration::class, $this->getRequestFixure('default')->getExchangeConfiguration());
    }

    public function testConfirmRequestInstance()
    {
        $ConfirmRequest = new Confirm\Type\Direct($this->getRequestFixure('default'));

        $this->assertInstanceOf(Confirm\Type\Direct::class, $ConfirmRequest);
    }


    public function testConfirmRequestBatchExecutionLazySocketConnection()
    {
        $messages = 100;

        $request = $this->getRequestFixure('default', $messages);

        $ConfirmRequest = new Confirm\Type\Direct($request);

        $this->assertNotNull($ConfirmRequest);

        $ConfirmRequest->execute();
    }

    public function _testConfirmRequestBatchExecutionSocketConnection()
    {
        $messages = 100;

        $request = $this->getRequestFixure('socketconnection', $messages);

        $ConfirmRequest = new Confirm\Type\Direct($request);

        $ConfirmRequest->execute();

        $this->assertNotNull($ConfirmRequest);
    }

    public function _testConfirmRequestBatchExecutionStreamConnection()
    {
        $messages = 100;

        $request = $this->getRequestFixure('streamconnection', $messages);

        $ConfirmRequest = new Confirm\Type\Direct($request);

        $ConfirmRequest->execute();

        $this->assertNotNull($ConfirmRequest);
    }

    public function _testConfirmRequestBatchExecutionLazyStreamConnection()
    {
        $messages = 100;

        $request = $this->getRequestFixure('lazystreamconnection', $messages);

        $ConfirmRequest = new Confirm\Type\Direct($request);

        $this->assertNotNull($ConfirmRequest);

        $ConfirmRequest->execute();
    }
}