<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 11:02
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\LowresTicket;

use Mittax\MediaConverterBundle\Ticket\Consumer\ConsumerAbstract;

/**
 * Class Producer
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ThumbnailTicket\Request
 */
class Consumer extends ConsumerAbstract
{
    /**
     * @var string
     */
    protected $_exchangeConfigurationTag = 'ffmpeg.lowres';

    /**
     * Classname to execute ticket
     *
     * @var string
     */
    protected $_executorClassName = '\Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\LowresTicket\Executor';
}