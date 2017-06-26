<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 11:02
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket;

use Mittax\MediaConverterBundle\Ticket\Consumer\ConsumerAbstract;

/**
 * Class Consumer
 * @package Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket
 */
class Consumer extends ConsumerAbstract
{
    /**
     * @var string
     */
    protected $_exchangeConfigurationTag = 'imagine.hires.cropping';

    /**
     * Classname to execute ticket
     *
     * @var string
     */
    protected $_executorClassName = '\Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket\Executor';

}