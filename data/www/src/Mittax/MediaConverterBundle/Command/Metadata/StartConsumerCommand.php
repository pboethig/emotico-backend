<?php

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 01.11.16
 * Time: 10:52
 */
namespace Mittax\MediaConverterBundle\Command\Metadata;

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
            ->setName('mittax:mediaconverter:metadata:startconsumer')

            ->setDescription('starts consumer for metadatachanel')

            ->setHelp("starts consumer for metadatachanel");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rabbitMQRequest = new \Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Consumer([]);

        $rabbitMQRequest->execute();

        $output->writeln([
            '',
        ]);

        $output->writeln([
            '----------------------------------------------------------------------------------------------------',
        ]);

        $output->write('consumers started');

        $rabbitMQRequest->execute();

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
