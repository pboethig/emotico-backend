<?php

namespace Mittax\MediaConverterBundle\Event\Listener\Messages;

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.05.17
 * Time: 19:52
 */
class SystemError
{
    /**
     * @var \Exception
     */
    public $exception = [];

    /**
     * @param \Exception $exception
     */
    public function _construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'message' =>
                [
                    'exception' => get_class($this->exception) ,
                    'message' => $this->exception->getMessage(),
                    'code' => $this->exception->getCode(),
                    'trace' => $this->exception->getTraceAsString(),
                ]
        ];
    }
}