<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 09.12.16
 * Time: 23:21
 */

namespace Mittax\RabbitMQBundle\Service\Consumer;

use Mittax\RabbitMQBundle\Exception\ConsumerClassNotFoundException;

class Factory
{
    /**
     * @var array
     */
    private $_consumerConfiguration;

    /**
     * @var IConsumer[]
     */
    private $_consumerList;

    /**
     * Factory constructor.
     * @param array $rawConfigData
     */
    public function __construct(Array $rawConfigData)
    {
        $this->_consumerConfiguration = $rawConfigData;

        $this->buildConsumerList($this->_consumerConfiguration);
    }

    /**
     * @param array $consumerConfiguration
     */
    public function buildConsumerList(Array $consumerConfiguration)
    {
        foreach ($consumerConfiguration as $className => $configArray)
        {
            $className = ucfirst($className);

            if (!class_exists($className))
            {
                throw new ConsumerClassNotFoundException('ConsumerClass: ' . $className . ' does not exist.');
            }

            /**
             * @var $object IConsumer
             */
            $object = new $className($configArray);

            $this->_consumerList[$className] = $object;
        }
    }

    /**
     * Returns consumer by name
     *
     * @param string $consumerName
     * @return IConsumer
     */
    public function getByName(string $consumerName)
    {
        foreach ($this->_consumerList as $name=> $consumer)
        {
            if ($name == $consumerName)
            {
                return $consumer;
            }
        }

        throw new ConsumerClassNotFoundException('ConsumerClass: ' . $consumerName . ' does not exist.');
    }
}