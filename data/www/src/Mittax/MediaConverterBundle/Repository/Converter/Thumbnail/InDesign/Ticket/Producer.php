<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 11:02
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\InDesign\Ticket;
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
    protected $_exchangeConfigurationTag = 'Indd';

    /**
     * Leave empty. Consumer will be called on INDD Serverside
     *
     * @return Process
     */
    public function buildProcess() : Process
    {
        $process = new Process('');

        return $process;
    }
}