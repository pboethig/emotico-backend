<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 16:53
 */

namespace Mittax\MediaConverterBundle\Service\Converter\Thumbnail;


use Mittax\MediaConverterBundle\Collection\StorageItem;
use Mittax\MediaConverterBundle\Collection\Thumbnail;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Producer;
use Mittax\MediaConverterBundle\Repository\Storage\Factory;
use Mittax\MediaConverterBundle\Repository\Storage\ItemRepository;
use Mittax\MediaConverterBundle\Repository\Storage\StorageRepositoryConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Factory as ThumbnailConverterFactory;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;

/**
 * Class Facade
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail
 */
class Facade
{
    /**
     * @var ContainerInterface
     */
    private $_container = null;

    /**
     * @var ItemRepository
     */
    private $_storageRepository = null;

    /**
     * @var StorageRepositoryConfig
     */
    private $_storageRepositoryConfig = null;

    /**
     * @var Factory
     */
    private $_storageRepositoryFactory = null;

    /**
     * @var \Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ItemRepository
     */
    private $_thumbnailRepository = null;

    /**
     * @var \Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Factory
     */
    private $_thumbnailConverterFactory = null;

    /**
     * @var StorageItem
     */
    private $_storageCollection = null;

    /**
     * @var \Mittax\RabbitMQBundle\Service\Connection\Factory
     */
    private $_rabbitMQConnectionFactory = null;

    /**
     * @var ITicket[]
     */
    private $_jobTickets = [];

    /**
     * Facade constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    /**
     * @param array $pathList
     * @param array|null $testLocalFileSystemConfig
     * @return bool
     */
    private function _init(Array $pathList, Array $testLocalFileSystemConfig = null) : bool
    {
        $this->_storageRepositoryConfig = new StorageRepositoryConfig($pathList, $testLocalFileSystemConfig);

        $this->_storageRepositoryFactory = new Factory($this->_storageRepositoryConfig);

        $this->_storageRepository = $this->_storageRepositoryFactory->getByUuid($this->_storageRepositoryConfig->getUuid());

        $this->_thumbnailConverterFactory = new ThumbnailConverterFactory($this->_storageRepositoryConfig);

        $this->_thumbnailRepository = $this->_thumbnailConverterFactory->getByUuid($this->_thumbnailConverterFactory->getRepositoryConfiguration()->getUuid());

        $this->_storageCollection = $this->_storageRepository->getCollection();

        $this->_rabbitMQConnectionFactory = $this->_container->get('mittax_rabbitmq.service.connection.factory');
        return true;
    }

    /**
     * Generates Thumbnails from given paths as array.
     *
     * @param array $pathList
     * @param array|null $testLocalFileSystemConfig / can be used to mock test filesystem
     * @return Thumbnail
     */
    public function generate(Array $pathList, Array $testLocalFileSystemConfig = null) : Array
    {
        $this->_init($pathList, $testLocalFileSystemConfig);

        $thumbnailData = $this->_thumbnailRepository->createThumbnails($this->_storageCollection);

        $this->_jobTickets = $this->_getJobTicketsFromThumbnailMetadata($thumbnailData);

        $this->_produceJobTickets();

        return $thumbnailData;
    }

    /**
     * @return bool
     */
    private function _produceJobTickets() : bool
    {
        return $this->_thumbnailRepository->executeJobTickets($this->_jobTickets);
    }

    /**
     * @param array $thumbnailData
     * @return ITicket[]
     */
    private function _getJobTicketsFromThumbnailMetadata(Array $thumbnailData) : Array
    {
        return $thumbnailData['jobTickets'];
    }

    /**
     * Returns created finedata if generate() after called
     *
     * @return \Mittax\MediaConverterBundle\Ticket\ITicket[]
     */
    public function getJobTickets()
    {
        if (empty($this->_jobTickets))
        {
            throw new BadMethodCallException('No Jobtickets available. Call generate() first');
        }

        return $this->_jobTickets;
    }
}