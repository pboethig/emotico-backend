<?php

namespace Mittax\MessageBundle\Tests\Clients\Twillo;

use Mittax\MessageBundle\Entity\Message;
use Mittax\MessageBundle\Service\MessageProvider\IResponse;
use Mittax\MessageBundle\Service\MessageProvider\Response;
use Mittax\MessageBundle\Service\MessageProvider\Sms\Twillo\Client;
use Mittax\MessageBundle\Service\MessageProvider\Sms\Twillo\ClientConfiguration;
use Mittax\MessageBundle\Tests\AbstractKernelTestCase;

class ClientTest extends AbstractKernelTestCase
{
    /**
     * @var Client
     */
    private $_client;

    /**
     * @var string
     */
    private $_testRecipientMobileNumer = '+4917672748496';

    /**
     * @var ClientConfiguration
     */
    private $_configuration;


    public function setUp()
    {
        parent::setUp();

        $this->_client = $this->container->get('mittax_message.provider.twillo.client');

        $this->_configuration = $this->container->get('mittax_message.provider.twillo.client.configuration');
    }

    /**
     * Test setting message
     */
    public function testSetMessage()
    {
        $this->_client->setMessage(new Message());

        $this->assertInstanceOf(Message::class, $this->_client->getMessage());
    }

    /**
     * Test getting method on the client
     */
    public function testGetMessage()
    {
        $this->assertNotNull($this->_client);
    }


    public function testConfiguration()
    {
        $number = $this->container->getParameter('mittax.message.twillo_number');

        $authtoken = $this->container->getParameter('mittax.message.twillo_authtoken');

        $sid = $this->container->getParameter('mittax.message.twillo_sid');

        $this->assertEquals($number, $this->_configuration->getNumber());

        $this->assertEquals($authtoken, $this->_configuration->getAuthToken());

        $this->assertEquals($sid, $this->_configuration->getSid());
    }

    public function _testSendMessage()
    {
        $message = new Message();

        $subject = 'Hey my tarikogut.';

        $content = 'Have a nice evening!';

        $message->setSubject($subject);

        $message->setContent($content);

        $responses = $this->_client->send($message, [$this->_testRecipientMobileNumer]);

        $currentResponse = $responses[$this->_testRecipientMobileNumer];

        $this->assertInstanceOf(IResponse::class, $currentResponse);

        $this->assertContains($content, $currentResponse->getMessage());
    }

    /**
     * Test the twillo send method mocked. If you want a real functional test uncomment prevous test (expensiv)
     */
    public function testMockSendMessage()
    {
        $message = new Message();

        $subject = 'Hey my tarikogut.';

        $content = 'Have a nice evening!';

        $message->setSubject($subject);

        $message->setContent($content);

        /**
         * Mock original client
         */
        $clientMock = $this->createMock(get_class($this->_client));

        /**
         * Build a fakeresponse
         */
        $responseWrapper = new Response(new \stdClass());
        $responseWrapper->setMessage($content)->setStatus('queued');

        /**
         * Add the fakeresponse to a fakeresponselist
         */
        $fakeResponses[$this->_testRecipientMobileNumer] = $responseWrapper;

        /**
         * Add send method to the clientmock and set returnvalue
         */
        $clientMock->method('send')->willReturn($fakeResponses);

        /**
         * "send" the message via mockmethod
         */
        /** @var $clientMock Client **/
        $responses = $clientMock->send($message, [$this->_testRecipientMobileNumer]);

        /**
         * Get single response to test from the processed send mock
         */

        /** @var $currentResponse IResponse **/
        $currentResponse = $responses[$this->_testRecipientMobileNumer];

        /**
         * Verify response on Messagetext, status and Responsetype
         */
        $this->assertInstanceOf(IResponse::class, $currentResponse);

        $this->assertContains($content, $currentResponse->getMessage());

        $this->assertEquals('queued', $currentResponse->getStatus());
    }
}