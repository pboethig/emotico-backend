<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 10.12.16
 * Time: 21:58
 */

namespace Mittax\MediaConverterBundle\Tests;


use PhpAmqpLib\Message\AMQPMessage;
use Mittax\MediaConverterBundle\Service\Logger\Factory;

abstract class RequestAbstract extends AbstractKernelTestCase
{
    /**
     * @var Reques
     */
    protected $_request;

    /**
     * @var array
     */
    protected $_exchangeDefinition;

    /**
     * @var Factory
     */
    protected $_loggerFactory;


    public function setUp()
    {
        parent::setUp();

        $this->_request = $this->container->get('mittax_rabbitmq.service.producer.confirm.request');

        $this->_exchangeDefinition = [
            'name' => 'testexchangetest',
            'queueName' => 'q_testexchangetest',
            'routingKey' => 'r_testexchangetest',
            'type' => 'direct',
            'auto_delete' => false,
            'durable' => true,
            'passive' => false,
            'parameters' => [],
            'nowait'=>false,
            'exclusive'=>false
        ];

        $this->_loggerFactory = $this->container->get('mittax_rabbitmq.logger.factory');
    }

    /**
     * @param string $connectionName
     * @return Request
     */
    public function getRequestFixure(string $connectionName, int $countMessages = 1000) : Request
    {
        $this->_request->setConnectionInterface($this->_getConnectionInterfaceMock($connectionName));

        $this->_request->setExchangeConfiguration($this->_getConfigurationMock());

        $this->_request->setMessages($this->_getMessagesMock($countMessages));

        $this->_request->setAcknowledgementCallback($this->_getAcknowledgmentCallbackMock());

        $this->_request->setNacknowledgementCallback($this->_getNacknowledgmentCallbackMock());

        $this->_request->setReturnListenerCallback($this->_getReturnCallbackMock());
        
        $this->_request->setBasicConsumeConfiguration($this->_getBasicConsumeMock());
        

        return $this->_request;
    }


    public function _getBasicConsumeMock()
    {
        $consumerClassName = 'Mittax\MediaConverterBundle\Service\Consumer\Configuration\Basic';

        /** @var  $consumer */
        $BasicConsumeConfiguration = $this->_consumerFactory->getByName($consumerClassName);

        return $BasicConsumeConfiguration;
    }


    /**
     * @param string $connectionName
     * @return IConnection
     */
    protected function _getConnectionInterfaceMock(string $connectionName)
    {
        return $this->_connectionFactory->getConnectionByName($connectionName);
    }

    /**
     * @return Configuration
     */
    protected function _getConfigurationMock()
    {
        return new Configuration($this->_exchangeDefinition);
    }

    /**
     * @return array
     */
    protected function _getMessagesMock($countMessages=1)
    {
        $messages = [];

        for ($i=1; $i <= $countMessages; $i++)
        {
            $message = new AMQPMessage(uniqid(), array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

            $messages[]= $message;
        }

        return $messages;
    }

    /**
     * @return \Closure
     */
    protected function _getAcknowledgmentCallbackMock()
    {
        return function (AMQPMessage $message)
        {
            $response = "Message acked with content " . $message->body;

            return $response;
        };
    }

    /**
     * @return \Closure
     */
    protected function _getNacknowledgmentCallbackMock()
    {
        return function (AMQPMessage $message)
        {
            $response = "Message not acked with content " . $message->body;

            $this->_loggerFactory->getLogger()->alert($response);

            return $response;
        };
    }

    /**
     * @return \Closure
     */
    protected function _getReturnCallbackMock()
    {
        return function ($replyCode, $replyText, $exchange, $routingKey, AMQPMessage $message) {

            $str = ':'.$replyCode.':'.$replyText.':'.$exchange.':'.$routingKey;

            $response = "Message returned with content " . $message->body . $str ;

            return $response;
        };
    }
}