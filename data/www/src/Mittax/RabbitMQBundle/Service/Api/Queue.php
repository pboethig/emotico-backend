<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 04.12.16
 * Time: 20:25
 */

namespace Mittax\RabbitMQBundle\Service\Api;

/**
 * Class Queue
 * @package Mittax\RabbitMQBundle\Service\Api
 */
class Queue extends ClientWrapper
{
    /**
     * @var EntityInterface
     */
    private $queue;

    /**
     * @param string $queueName
     * @return array|EntityInterface
     */
    public function getQueue(string $queueName)
    {
        $this->queue = $this->client->queues()->get($this->configuration->getVhost(), $queueName);

        return $this->queue;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->queue);
    }
}