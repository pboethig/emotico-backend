<?php

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 01.11.16
 * Time: 10:52
 */
namespace Mittax\MediaConverterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class StartConsumerCommand
 * @package Mittax\MediaConverterBundle\Command
 */
class CreateStorageItemCommand extends ContainerAwareCommand
{
    // ...
    protected function configure()
    {
        $this
            ->setName('mittax:mediaconverter:import:uploaded-files')

            ->setDescription('converts uploaded files to storageitem')

            ->setHelp("all files in folder upload will be converted to storage/assets item");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln([
            '',
        ]);

        $output->writeln([
            '----------------------------------------------------------------------------------------------------',
        ]);

        $output->write('start importing uploaded items');

        $this->clearRedisCache();

        \Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem::importFromUploadFolder();

        $output->writeln([
            '',
        ]);
        $output->writeln([
            '----------------------------------------------------------------------------------------------------',
        ]);
        $output->writeln([
            '',
        ]);

        $output->write('finished');
        $output->writeln([
            '',
        ]);
    }
}
