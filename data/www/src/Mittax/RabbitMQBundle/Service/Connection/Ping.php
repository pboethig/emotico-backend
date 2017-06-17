<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.12.16
 * Time: 20:54
 */

namespace Mittax\RabbitMQBundle\Service\Connection;


use Mittax\RabbitMQBundle\Exception\SimplePublishTestAfterReconnectFailedException;
use Mittax\RabbitMQBundle\Exception\SimplePublishTestFailedException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class Ping
{

    /**
     * Hold onto the connection
     * @var \PhpAmqpLib\Connection\AbstractConnection
     */
    protected $connection = null;
    /**
     * Hold onto the channel
     * @var \PhpAmqpLib\Channel\AbstractChannel|AMQPChannel
     */
    protected $channel = null;
    /**
     * Exchange name
     * @var string
     */
    protected $exchange = 'pingtestchanel';
    /**
     * Queue name
     * @var string
     */
    protected $queue = 'pingtestchanel';
    /**
     * Queue message body
     * @var string
     */
    protected $msgBody = 'a test message';

    public function __construct(IConnection $connectionInterface)
    {
        $this->connection = $connectionInterface->getConnection();
    }

    /**
     * Perform the test after the connection has already been setup
     */
    public function performTest()
    {
        // We need to a channel and exchange/queue
        $this->setupChannel();

        // Ensure normal publish then get works
        if (!$this->msgBody == $this->publishGet()->body)
        {
            throw new SimplePublishTestFailedException('"' . $this->msgBody . '" could not be identified');
        }

        // Reconnect the socket/stream connection
        $this->connection->reconnect();

        // Setup the channel and declarations again
        $this->setupChannel();

        // Ensure normal publish then get works (after reconnect attempt)
        if (!$this->msgBody == $this->publishGet()->body)
        {
            throw new SimplePublishTestAfterReconnectFailedException('"' . $this->msgBody . '" could not be identified');
        }

        return true;
    }

    /**
     * Setup the exchanges, and queues and channel
     */
    protected function setupChannel()
    {
        $this->channel = $this->connection->channel();

        $this->channel->exchange_declare($this->exchange, 'direct', false, false, false);

        $this->channel->queue_declare($this->queue);

        $this->channel->queue_bind($this->queue, $this->exchange, $this->queue);
    }
    /**
     * Publish a message, then get it immediately
     * @return \PhpAmqpLib\Message\AMQPMessage
     */
    protected function publishGet()
    {
        $msg = new AMQPMessage($this->msgBody, array(
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_NON_PERSISTENT,
            'correlation_id' => 'my_correlation_id',
            'reply_to' => 'my_reply_to'
        ));

        $this->channel->basic_publish($msg, $this->exchange, $this->queue);

        return $this->channel->basic_get($this->queue);
    }
}