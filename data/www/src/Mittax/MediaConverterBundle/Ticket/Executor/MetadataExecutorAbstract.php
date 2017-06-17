<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.01.17
 * Time: 15:44
 */

namespace Mittax\MediaConverterBundle\Ticket\Executor;


use Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Ticket;

/**
 * Class MetadataExecutorAbstract
 * @package Mittax\MediaConverterBundle\Ticket\Executor
 */
abstract class MetadataExecutorAbstract implements IExecutor
{
    /**
     * @var Ticket
     */
    protected $_ticket;

    abstract public function execute() : bool;

    public function __construct(Ticket $ticket)
    {
        $this->_ticket = $ticket;
    }
}