<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 11.12.16
 * Time: 09:04
 */

namespace Mittax\RabbitMQBundle\Service\Exchange;

use Mittax\RabbitMQBundle\Exception\PcntlSignalExtensionNotAailableException;
use Mittax\RabbitMQBundle\Service\Connection\IConnection;
use Mittax\RabbitMQBundle\Service\Producer\Confirm\IRequest;
use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Class DirectAbstract
 * @package Mittax\RabbitMQBundle\Service\Exchange
 */
abstract class DirectConfirmAbstract
{

    /**
     * @var Configuration
     */
    protected $_exchangeConfiguration;

    /**
     * @var IRequest
     */
    protected $_request;

    /**
     * @var IConnection
     */
    protected $_connectionInterface;

    /**
     * @var AMQPChannel
     */
    protected $_channel;

    /**
     * @var boolean
     */
    protected $_isProducer = false;

    /**
     * Confirm constructor.
     * @param IRequest $request
     */
    public function __construct(IRequest $request)
    {
        $this->_request = $request;
    }

    /**
     * @return bool
     */
    public function execute() : bool
    {
        $this->_exchangeConfiguration = $this->_request->getExchangeConfiguration();

        $this->_connectionInterface = $this->_request->getConnectionInterface();

        $this->_channel = $this->_connectionInterface->getConnection()->channel();

        if ($this->_isProducer)
        {
            //$this->registerCallbacks($this->_channel);

            //$this->_channel->confirm_select();
        }

        $this->declareQueue($this->_channel, $this->_exchangeConfiguration);

        $this->declareExchange($this->_channel, $this->_exchangeConfiguration);

        $this->bindQueue($this->_channel, $this->_exchangeConfiguration);

        return true;
    }

    /**
     * @return bool
     */
    public function registerSignalHandler() : bool
    {
        if (!extension_loaded('pcntl'))
        {
            return false;
        }

        define('AMQP_WITHOUT_SIGNALS', false);
        pcntl_signal(SIGTERM, [$this, 'signalHandler']);
        pcntl_signal(SIGHUP, [$this, 'signalHandler']);
        pcntl_signal(SIGINT, [$this, 'signalHandler']);
        pcntl_signal(SIGQUIT, [$this, 'signalHandler']);
        pcntl_signal(SIGUSR1, [$this, 'signalHandler']);
        pcntl_signal(SIGUSR2, [$this, 'signalHandler']);
        pcntl_signal(SIGALRM, [$this, 'alarmHandler']);

        return true;
    }


    /**
     * Signal handler
     *
     * @param  int $signalNumber
     * @return void
     */
    public function signalHandler($signalNumber)
    {
        echo 'Handling signal: #' . $signalNumber . PHP_EOL;
        global $consumer;
        switch ($signalNumber) {
            case SIGTERM:  // 15 : supervisor default stop
            case SIGQUIT:  // 3  : kill -s QUIT
                $consumer->stopHard();
                break;
            case SIGINT:   // 2  : ctrl+c
                $consumer->stop();
                break;
            case SIGHUP:   // 1  : kill -s HUP
                $consumer->restart();
                break;
            case SIGUSR1:  // 10 : kill -s USR1
                // send an alarm in 1 second
                pcntl_alarm(1);
                break;
            case SIGUSR2:  // 12 : kill -s USR2
                // send an alarm in 10 seconds
                pcntl_alarm(10);
                break;
            default:
                break;
        }
        return;
    }

    /**
     * Alarm handler
     *
     * @param  int $signalNumber
     * @return void
     */
    public function alarmHandler($signalNumber)
    {
        echo 'Handling alarm: #' . $signalNumber . PHP_EOL;
        echo memory_get_usage(true) . PHP_EOL;
        return;
    }



    /**
     * @param boolean $isProducer
     */
    public function setIsProducer($isProducer)
    {
        $this->_isProducer = $isProducer;
    }


    /**
     * @param AMQPChannel $channel
     * @param Configuration $exchangeConf
     * @return bool
     */
    public function declareExchange(AMQPChannel $channel, Configuration $exchangeConf) : bool
    {
            $channel->exchange_declare(
            $exchangeConf->getName(),
            $exchangeConf->getType(),
            $exchangeConf->isPassive(),
            $exchangeConf->isDurable(),
            $exchangeConf->isAutoDelete(),
            $exchangeConf->isInternal(),
            $exchangeConf->isNowait(),
            $exchangeConf->getParameters(),
            $exchangeConf->getTicket()
        );

        return true;
    }

    /**
     * @param AMQPChannel $channel
     * @param Configuration $exchangeConf
     * @return bool
     */
    public function declareQueue(AMQPChannel $channel, Configuration $exchangeConf) : bool
    {
        $channel->queue_declare($exchangeConf->getQueueName(),
            $exchangeConf->isPassive(),
            $exchangeConf->isDurable(),
            $exchangeConf->isExclusive(),
            $exchangeConf->isAutoDelete(),
            $exchangeConf->isNowait()
            );

        return true;
    }

    /**
     * @param AMQPChannel $channel
     * @param IConnection $connectionInterface
     * @return bool
     */
    public function closeExecution(AMQPChannel $channel, IConnection $connectionInterface) : bool
    {
        $channel->close();

        $connectionInterface->getConnection()->close();

        return true;
    }

    /**
     * @param AMQPChannel $channel
     * @param Configuration $exchangeConf
     * @return bool
     */
    public function bindQueue(AMQPChannel $channel, Configuration $exchangeConf) : bool
    {
        $channel->queue_bind($exchangeConf->getQueueName(), $exchangeConf->getName(), $exchangeConf->getRoutingKey(), $exchangeConf->isNowait());

        return true;
    }

    /**
     * @param AMQPChannel $channel
     * @return bool
     */
    public function qos(AMQPChannel $channel) : bool
    {
        /**
         * don't dispatch a new message to a worker until it has processed and
         * acknowledged the previous one. Instead, it will dispatch it to the
         * next worker that is not still busy.
        */

        $channel->basic_qos(
        null,   #prefetch size - prefetch window size in octets, null meaning "no specific limit"
        1,      #prefetch count - prefetch window in terms of whole messages
        null    #global - global=null to mean that the QoS settings should apply per-consumer, global=true to mean that the QoS settings should apply per-channel
        );

        return true;
    }

    /**
     * @param AMQPChannel $channel
     * @return bool
     */
    public function registerCallbacks(AMQPChannel $channel) : bool
    {
        $channel->set_ack_handler($this->_request->getAcknowledgementCallback());

        $channel->set_nack_handler($this->_request->getNacknowledgementCallback());

        $channel->set_return_listener($this->_request->getReturnListener());

        return true;
    }
}