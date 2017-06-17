<?php

namespace Mittax\MessageBundle\Tests\Service;

use Mittax\MessageBundle\Tests\AbstractKernelTestCase;


class PhoneNumber extends AbstractKernelTestCase
{
    /**
     * @var string
     */
    private $_testRecipientMobileNumer = '+4917672748496';

    /**
     * @var \Mittax\MessageBundle\Service\PhoneNumber
     */
    private $_numberService;

    public function setUp()
    {
        parent::setUp();
        
        $this->_numberService = $this->container->get('mittax_message.phonenumber');
    }



    public function testParseNumber()
    {
        $swissNumberStr = "+41 44 668 18 00";
        
        $phoneNumberObject = $this->_numberService->parse($swissNumberStr, 'CH');

        $this->assertEquals($phoneNumberObject->getCountryCode(), 41);
    }
    
    public function testValidateGerman()
    {
        $validationResult = $this->_numberService->validate($this->_testRecipientMobileNumer, 'DE');

        $this->assertTrue($validationResult);
    }

    /**
     * Test setting message
     */
    public function testValidateSwissNumber()
    {
        $swissNumberStr = "+41 44 668 18 00";

        $validationResult = $this->_numberService->validate($swissNumberStr, 'CH');

        $this->assertTrue($validationResult);
    }
}