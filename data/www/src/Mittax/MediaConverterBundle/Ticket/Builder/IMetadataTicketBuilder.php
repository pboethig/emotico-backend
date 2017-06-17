<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.01.17
 * Time: 16:50
 */

namespace Mittax\MediaConverterBundle\Ticket\Builder;


use Mittax\MediaConverterBundle\Service\Metadata\Generator\IMetadataGenerator;
use Mittax\MediaConverterBundle\Ticket\ITicketBuilder;

interface IMetadataTicketBuilder extends ITicketBuilder
{
    /**
     * @return IMetadataGenerator
     */
    public function getGenerator() : IMetadataGenerator;
}