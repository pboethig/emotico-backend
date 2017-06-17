<?php

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 01.11.16
 * Time: 10:52
 */
namespace Mittax\MediaConverterBundle\Command\Thumbnail\InDesign;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Jack\Symfony\ProcessManager;
use Symfony\Component\Process\Process;

/**
 * Class StartConsumerCommand
 * @package Mittax\MediaConverterBundle\Command
 */
class StartBatchConsumersCommand extends ContainerAwareCommand
{
    // ...
    protected function configure()
    {
        $this
            ->setName('mittax:mediaconverter:thumbnail:indesign:startbatchconsumers')

            ->setDescription('starts a list of rabbitmq thumbnail consumer')

            ->addArgument('consumers', InputArgument::REQUIRED, 'Number of cosumers')

            ->setHelp("This command starts n consumer to process thumbnail convertion messages");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {


    }
}
