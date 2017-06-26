<?php

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 01.11.16
 * Time: 10:52
 */
namespace Mittax\MediaConverterBundle\Command\Cropping\Imagine;

use Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket\Consumer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class StartConsumerCommand
 * @package Mittax\MediaConverterBundle\Command
 */
class StartConsumerCommand extends ContainerAwareCommand
{
    /**
     * @var ContainerInterface
     */
    private $_container;

    // ...
    protected function configure()
    {
        $this
            ->setName('mittax:mediaconverter:cropping:imagine:startconsumer')

            ->setDescription('starts a rabbitmq thumbnail consumer')

            ->setHelp("This command starts n consumer to process hires cropping messages");
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
