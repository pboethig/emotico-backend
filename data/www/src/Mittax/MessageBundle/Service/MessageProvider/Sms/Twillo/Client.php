<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.10.16
 * Time: 22:59
 */

namespace Mittax\MessageBundle\Service\MessageProvider\Sms\Twillo;

use Mittax\MessageBundle\Entity\Message;
use Mittax\MessageBundle\Service\MessageProvider\IResponse;
use Mittax\MessageBundle\Service\MessageProvider\Response;
use Mittax\MessageBundle\Service\MessageProvider\Sms\ISmsProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dump\Container;

class Client implements ISmsProvider
{
    /**
     * @var \Twilio\Rest\Client
     */
    private $_client;

    /**
     * @var Container
     */
    private $_container;

    /**
     * @var Message
     */
    private $_message;

    /**
     * Client constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;

        $this->_client = new \Twilio\Rest\Client($this->getConfig()->getSid(), $this->getConfig()->getAuthToken());
    }

    /**
     * @param Message $message
     * @return $this
     */
    public function setMessage(Message $message)
    {
        $this->_message = $message;
        
        return $this;
    }

    /**
     * @return \Mittax\MessageBundle\Service\MessageProvider\Sms\Twillo\ClientConfiguration
     */
    public function getConfig()
    {
        return $this->_container->get('mittax_message.provider.twillo.client.configuration');
    }

    /**
     * @param Message $message
     * @param array $recipientPhoneNumbers
     * @return IResponse[]
     */
    public function send(Message $message, Array $recipientPhoneNumbers) : Array
    {
        $this->setMessage($message);

        $responses = [];

        $submissionData = [
            'from' => $this->getConfig()->getNumber(),
            'body' => $message->getContent()
        ];

        foreach ($recipientPhoneNumbers as $phoneNumber)
        {
            $serviceResponse = $this->_client->messages->create($phoneNumber, $submissionData);

            $responseWrapper = new Response($serviceResponse);

            $responseWrapper->setMessage($serviceResponse->body)->setStatus($serviceResponse->status);

            $responses[$phoneNumber] = $responseWrapper;
        }

        return $responses;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
       return $this->_message;
    }
}