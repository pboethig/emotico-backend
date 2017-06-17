<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 30.10.16
 * Time: 00:17
 */

namespace Mittax\MessageBundle\Service\MessageProvider\Sms;

use Mittax\MessageBundle\Entity\Message;
use Mittax\MessageBundle\Service\MessageProvider\IMessageProvider;
use Mittax\MessageBundle\Service\MessageProvider\IResponse;

interface ISmsProvider
{

    /**
     * @param Message $message
     * @param array $recipientMobileNumbers
     * @return IResponse[]
     */
    public function send(Message $message, array $recipientMobileNumbers) : Array;

    /**
     * @return IMessageProvider
     */
    public function getMessage();
}