<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 11:02
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\ThumbnailTicket;
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
    protected $_exchangeConfigurationTag = 'ffmpeg';

    /**
     * Override this method to imlement a custom command
     *
     * @return Process
     */
    public function buildProcess() : Process
    {
        $process = new Process('php /var/www/app/console mittax:mediaconverter:thumbnail:ffmpeg:startconsumer');

        return $process;
    }
}