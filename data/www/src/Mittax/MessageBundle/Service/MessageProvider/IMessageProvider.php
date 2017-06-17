<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 30.10.16
 * Time: 00:17
 */

namespace Mittax\MessageBundle\Service\MessageProvider;


use Mittax\MessageBundle\Entity\Message;
use Symfony\Component\HttpFoundation\Response;

interface IMessageProvider
{

    /**
     * @param Message $message
     * @param array $recipients
     * @return IResponse
     */
    public function send(Message $message, array $recipients) : IResponse;

    /**
     * Set message.
     * @param Message $message
     * @return IMessageProvider
     */
    public function setMessage(Message $message);

    /**
     * @return IMessageProvider
     */
    public function getMessage();
}