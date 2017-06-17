<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 20.12.16
 * Time: 17:21
 */

namespace Mittax\MediaConverterBundle\Repository\Converter;

use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Service\Format\SupportedFormatsBuilder;
use Mittax\MediaConverterBundle\ValueObjects\ConverterConfig;
use Mittax\MediaConverterBundle\ValueObjects\Version;
use Mittax\ObjectCollection\ICollectionItem;

/**
 * Interface IConverter
 * @package Mittax\MediaConverterBundle\Repository\Converter
 */
interface IConverter extends ICollectionItem
{

    /**
     * @return ConverterConfig
     */
    public function getConverterConfig() : ConverterConfig;

    /**
     * @return string
     */
    public function getUuid() : string ;

    /**
     * @param ITicket $jobTicket
     * @return mixed
     */
    public function attachJobTicket(ITicket $jobTicket);

    /**
     * @return ITicket
     */
    public function getJobTickets() : Array;

    /**
     * @return string
     */
    public function getName() : string ;

}