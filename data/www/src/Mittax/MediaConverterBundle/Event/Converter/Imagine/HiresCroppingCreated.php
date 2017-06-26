<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 05.01.17
 * Time: 10:30
 */

namespace Mittax\MediaConverterBundle\Event\Converter\Imagine;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Ticket\Cropping\ICroppingTicket;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class HiresCroppingCreated
 * @package Mittax\MediaConverterBundle\Event\Converter\Ffmpeg
 */
class HiresCroppingCreated extends Event
{
    const NAME = 'imagine.hires.cropping.created';

    /**
     * @var StorageItem
     */
    protected $_storageItem;

    /**
     * @var ICroppingTicket
     */
    protected $jobTicket;

    /**
     * HiresCroppingCreated constructor.
     * @param ICroppingTicket $jobTicket
     */
    public function __construct(ICroppingTicket $jobTicket)
    {
        $this->jobTicket = $jobTicket;
    }

    /**
     * @return ICroppingTicket
     */
    public function getJobTicket()
    {
        return $this->jobTicket;
    }
}