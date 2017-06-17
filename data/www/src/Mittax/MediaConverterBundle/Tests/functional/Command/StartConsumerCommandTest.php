<?php

namespace Mittax\MessageBundle\Tests\Command;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Mittax\WsseBundle\Command\GenerateHeaderCommand;
use Symfony\Component\Console\Tester\CommandTester;


require_once __DIR__. '/../../../../../../app/autoload.php';

class StartConsumerCommandTest extends KernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testCommands()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $application->add(new GenerateHeaderCommand());

        $command = $application->find('mittax:mediaconverter:thumbnail:imagine:startconsumer');

        new CommandTester($command);

        $command = $application->find('mittax:mediaconverter:thumbnail:ffmpeg:startconsumer');

        new CommandTester($command);

        $command = $application->find('mittax:mediaconverter:thumbnail:ffmpeg:startbatchconsumers');

        new CommandTester($command);

        $command = $application->find('mittax:mediaconverter:thumbnail:imagine:startbatchconsumers');

        new CommandTester($command);

        $command = $application->find('mittax:mediaconverter:thumbnail:ffmpeg:lowres:startconsumer');

        new CommandTester($command);

        $this->assertNotNull($command);
    }
}
