<?php

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 01.11.16
 * Time: 10:52
 */
namespace Mittax\RabbitMQBundle\Command;

use Mittax\RabbitMQBundle\Exception\PingFailedException;
use Mittax\RabbitMQBundle\Service\Connection\Factory;
use Mittax\RabbitMQBundle\Service\Connection\Ping;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class PingCommand extends ContainerAwareCommand
{
    /**
     * @var ContainerInterface
     */
    private $_container;

    /**
     * @var \Mittax\RabbitMQBundle\Service\Connection\Factory
     */
    private $_connectionFactory;

    // ...
    protected function configure()
    {
        $this
            ->setName('mittax:rabbitmq:ping')

            ->setDescription('tests if rabbitmq connection is available')

            ->addArgument('connectionName', InputArgument::REQUIRED, 'Which connection you want to ping?')

            ->setHelp("This command allows you to validate the connection of your rabbitmq");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * Get configured encoderClass
         */
        $this->_container = $this->getContainer();

        $this->_connectionFactory = $this->_container->get('mittax_rabbitmq.service.connection.factory');

        $connectionName = $input->getArgument('connectionName');

        $connectionInterface = $this->_connectionFactory->getConnectionByName($connectionName);

        $connectionConfiguration = $connectionInterface->getConfiguration();

        try
        {
            $ping = new Ping($connectionInterface);

            $result = $ping->performTest();

        }catch (\Symfony\Component\Debug\Exception\ContextErrorException $e)
        {
            throw new PingFailedException('Ping to host: ' . $connectionConfiguration->getHost() . ' on port: ' . $connectionConfiguration->getPort() . ' failed' . PHP_EOL . 'Inner Message: ' . $e->getMessage() . PHP_EOL);
        }

        $output->writeln([
            '----------------------------------------------------------------------------------------------------',
        ]);

        if ($result)
        {
            $output->write('Ping is okay. Host: "' . $connectionConfiguration->getHost() . '" is processing messages on port: '. $connectionConfiguration->getPort());
        }

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
