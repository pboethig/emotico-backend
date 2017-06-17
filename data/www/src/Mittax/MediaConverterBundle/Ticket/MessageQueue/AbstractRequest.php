<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 18:36
 */

namespace Mittax\MediaConverterBundle\Ticket\MessageQueue;
use Mittax\MediaConverterBundle\Exception\ExchangeConfigNotFoundException;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\RabbitMQBundle\Service\Connection\IConnection;
use Mittax\RabbitMQBundle\Service\Exchange\Configuration;
use PhpAmqpLib\Message\AMQPMessage;
use Mittax\RabbitMQBundle\Service\Connection\Factory;
use Mittax\RabbitMQBundle\Service\Connection\Configuration\Factory as ConnectionConfigurationFactory;
/**
 * Class AbstractRequest
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ThumbnailTicket\Request
 */
abstract class AbstractRequest implements IRequest
{
    /**
     * @var string
     */
    protected $_exchangeConfigurationTag;

    /**
     * @var \Mittax\RabbitMQBundle\Service\Consumer\Factory
     */
    protected $_consumerFactory;

    /**
     * @var \Mittax\RabbitMQBundle\Service\Producer\Confirm\Request
     */
    protected $_request;

    /**
     * @var Factory
     */
    protected $_connectionFactory;

    /**
     * @return bool
     */
    abstract public function execute() : bool;


    /**
     * AbstractRequest constructor.
     * @param array $jobTickets
     */
    public function __construct(Array $jobTickets)
    {
        $this->_connectionFactory = $this->buildConnectionFactory();

        $this->_request = new \Mittax\RabbitMQBundle\Service\Producer\Confirm\Request();

        $this->_request->setConnectionInterface($this->getConnectionInterface('default'));

        $this->_request->setExchangeConfiguration($this->getConfiguration());

        $this->_request->setMessages($this->buildMessages($jobTickets));

        $this->_request->setAcknowledgementCallback($this->getAcknowledgmentCallback());

        $this->_request->setNacknowledgementCallback($this->getNacknowledgmentCallback());

        $this->_request->setReturnListenerCallback($this->getReturnCallback());

        $this->_consumerFactory = new \Mittax\RabbitMQBundle\Service\Consumer\Factory(Config::getExchangeConfiguration()['consumers']);

        $this->_request->setBasicConsumeConfiguration($this->_getBasicConsumer());

    }

    /**
     * @return Factory
     */
    public function buildConnectionFactory()
    {
        $rawData = Config::getRabbitMQConfig();

        return new Factory(new ConnectionConfigurationFactory($rawData['connections']));
    }

    protected function _getBasicConsumer()
    {
        $consumerClassName = 'Mittax\RabbitMQBundle\Service\Consumer\Configuration\Basic';

        /** @var  $consumer */
        $BasicConsumeConfiguration = $this->_consumerFactory->getByName($consumerClassName);

        return $BasicConsumeConfiguration;
    }

    /**
     * @param array $jobTickets
     * @return AMQPMessage[]
     */
    public function buildMessages(Array $jobTickets) : Array
    {
        $messages = [];

        foreach ($jobTickets as $jobTicket)
        {
            $messages[] = new AMQPMessage($jobTicket->serialize(), array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        }

        return $messages;
    }

    /**
     * @return callable
     */
    public function getReturnCallback() : callable
    {
        return function ($replyCode, $replyText, $exchange, $routingKey, AMQPMessage $message) {

            $str = ':'.$replyCode.':'.$replyText.':'.$exchange.':'.$routingKey;

            $response = "Message returned with content " . $message->body . $str ;

            return $response;
        };
    }

    /**
     * @param string $connectionName
     * @return IConnection
     */
    public function getConnectionInterface(string $connectionName) : IConnection
    {
        return $this->_connectionFactory->getConnectionByName($connectionName);
    }

    /**
     * @return callable
     */
    public function getAcknowledgmentCallback() : callable
    {
        return function (AMQPMessage $message)
        {
            $response = "Message acked with content " . $message->body;

            return $response;
        };
    }

    /**
     * @return callable
     */
    public function getNacknowledgmentCallback() : callable
    {
        return function (AMQPMessage $message)
        {
            $response = "Message not acked with content " . $message->body;

            $this->_loggerFactory->getLogger()->alert($response);

            return $response;
        };
    }

    /**
     * @return Configuration
     */
    public function getConfiguration() : Configuration
    {

        if (!isset(Config::getExchangeConfiguration()['producers']['thumbnails'][$this->_exchangeConfigurationTag]))
        {
            throw new ExchangeConfigNotFoundException('Exchangeconfig not found for: '.$this->_exchangeConfigurationTag);
        }

        $exchangeConfiguration = Config::getExchangeConfiguration()['producers']['thumbnails'][$this->_exchangeConfigurationTag];

        return new Configuration($exchangeConfiguration);
    }
}