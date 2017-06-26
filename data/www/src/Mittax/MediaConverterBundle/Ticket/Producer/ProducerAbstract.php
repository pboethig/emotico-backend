<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 05.01.17
 * Time: 12:19
 */

namespace Mittax\MediaConverterBundle\Ticket\Producer;

use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Ticket\MessageQueue\AbstractRequest;
use Mittax\RabbitMQBundle\Service\Producer\Confirm\Type\Direct;
use Symfony\Component\Process\Process;

use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Consumer;
/**
 * Class ProducerAbstract
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\ThumbnailTicket\Producer
 */
abstract class ProducerAbstract  extends AbstractRequest
{
    /**
     * @return bool
     */
    public function execute() : bool
    {
        $producer = new Direct($this->_request);

        $producer->execute();

        return true;
    }
}