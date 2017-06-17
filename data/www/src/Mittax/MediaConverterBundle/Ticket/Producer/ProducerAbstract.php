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
     * @var array
     */
    private static $startedConsumers = [];

    /**
     * Override this method to imlement a custom command
     *
     * @return Process
     */
    abstract public function buildProcess() : Process;

    /**
     * @return bool
     */
    public function execute() : bool
    {
        $producer = new Direct($this->_request);

        $producer->execute();

        /**
         * If activated start cosumer cli processes automaticly
         * @see app/config/mediaconverter.yml
         */
        $autostartConsumerProcesses = Config::getMediaConverterConfig()['exchangeConfiguration']['autostartConsumers']['active'];

        if ($autostartConsumerProcesses)
        {
            //self::startConsumer($this->buildProcess());
        }

        return true;
    }

    /**
     * @param Process $process
     */
    public static function startConsumer(Process $process)
    {
        $maxConsumerInstances = Config::getMediaConverterConfig()['exchangeConfiguration']['autostartConsumers']['instances'];

        $cacheKey = crc32($process->getCommandLine());

        if (!isset(self::$startedConsumers[$cacheKey]))
        {
            /**
             * Change dir to root before executing process
             */
            $root = "/var/www";
            $chDirProcess = new Process('cd ' . $root);
            $chDirProcess->start();


            for ($i = 0; $i < $maxConsumerInstances; $i++)
            {
                /** @var $processes Process[] */
                $processes[$i] = new Process($process->getCommandLine());

                $processes[$i]->setTimeout(81400);

                $processes[$i]->run();

                $processes[$i]->wait(function ($type, $buffer) {

                });
            }

            self::$startedConsumers[$cacheKey]=$cacheKey;
        }
    }
}