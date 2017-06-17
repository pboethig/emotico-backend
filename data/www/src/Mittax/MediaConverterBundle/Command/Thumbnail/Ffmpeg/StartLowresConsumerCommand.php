<?php

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 01.11.16
 * Time: 10:52
 */
namespace Mittax\MediaConverterBundle\Command\Thumbnail\Ffmpeg;

use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\LowresTicket\Consumer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class StartConsumerCommand
 * @package Mittax\MediaConverterBundle\Command
 */
class StartLowresConsumerCommand extends ContainerAwareCommand
{
    /**
     * @var ContainerInterface
     */
    private $_container;

    // ...
    protected function configure()
    {
        $this
            ->setName('mittax:mediaconverter:thumbnail:ffmpeg:lowres:startconsumer')

            ->setDescription('starts a rabbitmq video lowres consumer')

            ->setHelp("This command starts a consumer to process lowres generation");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rabbitMQConsumer = new Consumer([]);

        $output->writeln([
            '',
        ]);

        $output->writeln([
            '----------------------------------------------------------------------------------------------------',
        ]);

        $output->write('consumers started');

        $rabbitMQConsumer->execute();

        $output->writeln([
            '',
        ]);
        $output->writeln([
            '----------------------------------------------------------------------------------------------------',
        ]);
        $output->writeln([
            '',
        ]);
    }
}
