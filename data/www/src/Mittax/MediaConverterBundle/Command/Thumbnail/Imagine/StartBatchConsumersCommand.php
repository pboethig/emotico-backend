<?php

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 01.11.16
 * Time: 10:52
 */
namespace Mittax\MediaConverterBundle\Command\Thumbnail\Imagine;


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
            ->setName('mittax:mediaconverter:thumbnail:imagine:startbatchconsumers')

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

        $root = __DIR__ . '../../../../../../';

        $process = new Process('cd ' . $root);
        $process->start();

        $proc_mgr = new ProcessManager();


        $processes = [];

        $output->writeln([
            '',
        ]);

        $output->write('processing '. $input->getArgument('consumers') . ' consumers');

        for ($i = 0; $i < $input->getArgument('consumers'); $i++)
        {
            array_push($processes, new Process('php bin/console mittax:mediaconverter:thumbnail:imagine:startconsumer'));
        }

        $max_parallel_processes = count($processes);
        $polling_interval = 500;
        $proc_mgr->runParallel($processes, $max_parallel_processes, $polling_interval);

        $output->writeln([
            '',
        ]);

        $output->writeln([
            '',
        ]);
    }
}
