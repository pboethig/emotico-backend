<?php

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 01.11.16
 * Time: 10:52
 */
namespace Mittax\MediaConverterBundle\Command\Metadata;

use Mittax\MediaConverterBundle\Repository\Storage\ItemRepository;

use Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Builder;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Producer;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Ticket;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class StartConsumerCommand
 * @package Mittax\MediaConverterBundle\Command
 */
class StartProducerCommand extends ContainerAwareCommand
{
    // ...
    protected function configure()
    {
        $this
            ->setName('mittax:mediaconverter:metadata:startproducer')

            ->setDescription('generates metadata by given storagepath')

            ->addArgument('storagepath', InputArgument::REQUIRED, 'Flysystem storage path')

            ->setHelp("If starts a producer to extract metadata");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storagePath = $input->getArgument('storagePath');

        $hasItem = Filesystem::getCachedAdapter('storage')->has($storagePath);

        if (!$hasItem)
        {
            throw new \InvalidArgumentException('storagePath does not exist');
        }

        $storageItem = ItemRepository::getByPath($storagePath);

        $ticket = new Ticket(new Builder($storageItem));
        $rabbitMQRequest = new Producer([$ticket]);

        $rabbitMQRequest->execute();

        $output->writeln([
            '',
        ]);

        $output->writeln([
            '----------------------------------------------------------------------------------------------------',
        ]);

        $output->write('producers started');

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
