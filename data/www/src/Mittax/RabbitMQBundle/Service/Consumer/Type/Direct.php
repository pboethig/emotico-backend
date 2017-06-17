<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 04.12.16
 * Time: 17:00
 */

namespace Mittax\RabbitMQBundle\Service\Consumer\Type;

use Mittax\RabbitMQBundle\Exception\Handler\Consumer;
use Mittax\RabbitMQBundle\Service\Exchange\DirectConfirmAbstract;
use PhpAmqpLib\Channel\AMQPChannel;
use Symfony\Component\Config\Definition\Exception\Exception;

class Direct extends DirectConfirmAbstract
{

    /**
     * @return bool
     */
    public function execute() : bool
    {
        try
        {

            $this->registerSignalHandler();

            parent::execute();

            $this->qos($this->_channel);

            $this->consume($this->_channel);

            $this->closeExecution($this->_channel, $this->_connectionInterface);

        }catch (\PhpAmqpLib\Exception\AMQPIOException $e)
        {
            new Consumer($e);
        }

        return true;
    }

    /**
     * indicate interest in consuming messages from a particular queue. When they do
     * so, we say that they register a consumer or, simply put, subscribe to a queue.
     * Each consumer (subscription) has an identifier called a consumer tag
     *
     * @param AMQPChannel $channel
     * @return bool
     */
    public function consume(AMQPChannel $channel) : bool
    {
        $basicConsumeConfig = $this->_request->getBasicConsumeConfiguration();

        $channel->basic_consume(
            $basicConsumeConfig->getQueue(),
            $basicConsumeConfig->getConsumerTag(),
            $basicConsumeConfig->isNoLocal(),
            $basicConsumeConfig->isNoAck(),
            $basicConsumeConfig->isExclusive(),
            $basicConsumeConfig->isNowait(),
            array($this, 'process_message') #callback
        );

        while(count($channel->callbacks)) {

            $channel->wait();
        }

        return true;
    }

    /**
     * @param \PhpAmqpLib\Message\AMQPMessage $message
     */
    function process_message($message)
    {


        $returnListener = $this->_request->getReturnListener();

        $returnListener('999', 'success', $this->_request->getExchangeConfiguration()->getName(), $this->_request->getExchangeConfiguration()->getRoutingKey(), $message);

        $channel = $message->delivery_info['channel'];

        //$channel->basic_cancel($message->delivery_info['consumer_tag']);

        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

        return false;
    }
}