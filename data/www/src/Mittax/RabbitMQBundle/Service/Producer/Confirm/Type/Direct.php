<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 04.12.16
 * Time: 17:00
 */

namespace Mittax\RabbitMQBundle\Service\Producer\Confirm\Type;

use Mittax\RabbitMQBundle\Service\Exchange\DirectConfirmAbstract;


class Direct extends DirectConfirmAbstract
{
    /**
     * @return bool
     */
    public function execute() : bool
    {
        $this->setIsProducer(true);

        parent::execute();

        foreach ($this->_request->getMessages() as $key=>$message)
        {
            $this->_channel->basic_publish($message, $this->_exchangeConfiguration->getName(),$this->_exchangeConfiguration->getRoutingKey(), true);
        }

        $this->closeExecution($this->_channel, $this->_connectionInterface);

        return true;
    }
}