<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 11:02
 */

namespace Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket;
use Mittax\MediaConverterBundle\Ticket\Producer\ProducerAbstract;
use Symfony\Component\Process\Process;

/**
 * Class Producer
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ThumbnailTicket\Request
 */
class Producer extends ProducerAbstract
{
    /**
     * @var string
     */
    protected $_exchangeConfigurationTag = 'metadata.extract';

    /**
     * Override this method to imlement a custom command
     *
     * @return Process
     */
    public function buildProcess() : Process
    {
        $process = new Process('php bin/console mittax:mediaconverter:metadata:startconsumer');

        return $process;
    }
}