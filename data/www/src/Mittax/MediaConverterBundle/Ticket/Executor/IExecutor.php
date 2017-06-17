<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 08.01.17
 * Time: 13:38
 */

namespace Mittax\MediaConverterBundle\Ticket\Executor;


interface IExecutor
{
    /**
     * @return bool
     */
    public function execute():bool;

}