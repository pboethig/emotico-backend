<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 20.12.16
 * Time: 18:14
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail;


use Mittax\MediaConverterBundle\Collection\Thumbnail;
use Mittax\MediaConverterBundle\Collection\ThumbnailConverter;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Repository\Converter\IConverter;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\ThumbnailTicket\Producer;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Builder;
use Mittax\MediaConverterBundle\Repository\RepositoryAbstract;
use \Mittax\MediaConverterBundle\Collection\StorageItem as StorageItemCollection;
use Mittax\MediaConverterBundle\Exception\NoConverterForFormatFoundException;
use Mittax\MediaConverterBundle\Event\Converter\NoConverterForFormatFoundException as NotFountEvent;
use Mittax\MediaConverterBundle\Ticket\MessageQueue\IRequest;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\IThumbnailTicket;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class ItemRepository
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail
 */
class ItemRepository extends RepositoryAbstract
{
    /**
     * @var RepositoryConfig
     */
    private $_repositoryConfig;

    /**
     * @var ThumbnailConverter
     */
    private $_collection;

    /**
     * @var IConverter[]
     */
    private static $_formatToConverterMap;

    /**
     * ItemRepository constructor.
     * @param RepositoryConfig $config
     */
    public function __construct(RepositoryConfig $config)
    {
        $this->_repositoryConfig = $config;

        $this->_collection = $this->buildCollection($config);

        $config->buildFormatToConverterMap();
    }

    /**
     * @return IConverter
     */
    public function getFirstItem() : IConverter
    {
        return $this->_collection->getFirstItem();
    }

    /**
     * IConverter[]
     */
    public function getAllItems() : Array
    {
        return $this->_collection->getAllItems();
    }

    /**
     * @return ThumbnailConverter
     */
    public function getCollection() : ThumbnailConverter
    {
        return $this->_collection;
    }

    /**
     * @param StorageItem $storageItem
     * @return IConverter|\Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\IConverter
     */
    public function getByStorageItem(StorageItem $storageItem) : \Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\IConverter
    {
        /**
         * The formatlist is huge, so cache the result static
         */
        if (isset(self::$_formatToConverterMap[$storageItem->getExtension()]))
        {
            return self::$_formatToConverterMap[$storageItem->getExtension()];
        }

        foreach ($this->getCollection()->getAllItems() as $converter)
        {
            foreach ($converter->getConverterConfig()->getFormats() as $format)
            {
                if (strtolower($format->getExtension()) == strtolower($storageItem->getExtension()) || '.' . strtolower($format->getExtension()) == strtolower($storageItem->getExtension()))
                {
                    self::$_formatToConverterMap[$storageItem->getExtension()] = $converter;

                    return $converter;
                }
            }
        }

        throw new NoConverterForFormatFoundException('no converter found for this format:' . $storageItem->getExtension());
    }

    /**
     * @param string $converterName
     * @return IConverter
     */
    public function getByName(string $converterName) : IConverter
    {
        $filteredCollection = $this->_collection->filterByPropertyNameAndValue('name', $converterName);

        if ($filteredCollection->count() < 1)
        {
            throw new NoConverterForFormatFoundException('Converter not found:' . $converterName);
        }

        return $filteredCollection->getFirstItem();
    }

    /**
     * @param StorageItemCollection $collection
     * @return array
     */
    public function createThumbnails(StorageItemCollection $collection) : Array
    {
        $thumbnailList = [];

        $thumbnailTickets = [];

        foreach ($collection->getAllItems() as $storageItem)
        {
            $converter = null;

            //prevent temporary indesign open document files
            if($storageItem->getExtension()=="idlk" || empty($storageItem->getExtension())) continue;

            try
            {
                $converter = $this->getByStorageItem($storageItem);
            }
            catch (\Exception $e)
            {
                Dispatcher::dispatch(NotFountEvent::NAME, new NotFountEvent($storageItem));

                continue;
            }

            /** @var  $thumbnailTicketBuilder  Builder*/
            $thumbnailTicketBuilder = $converter->createThumbnails($storageItem);

            //metadata
            foreach ($thumbnailTicketBuilder->getGeneratedThumbnails() as $thumbnail)
            {
                $thumbnailList[$thumbnail->getUuid()] = $thumbnail;
            }

            $jobTickets = $thumbnailTicketBuilder->getJobTickets();

            //jobs to create finedata
            foreach ($jobTickets as $jobTicket)
            {
                $thumbnailTickets[$converter->getConverterConfig()->getProducerClassName()][$jobTicket->getJobId()] = $jobTicket;
            }
        }

        $thumbnailCollection = new Thumbnail($thumbnailList);

        return  ['collection'=>$thumbnailCollection, 'jobTickets' => $thumbnailTickets];
    }

    /**
     * @param array $jobTicketsGroupedByProducerName
     * @return bool
     */
    public function executeJobTickets(Array $jobTicketsGroupedByProducerName)
    {
        $jobTicketList = [];

        foreach ($jobTicketsGroupedByProducerName as $producerCLassName=>$jobTicketData)
        {
            foreach ($jobTicketData as $jobTicket)
            {
                $jobTicketList[$producerCLassName][] = $jobTicket;
            }
        }

        foreach ($jobTicketList as $producerCLassName => $_jobTicketList)
        {
            /**
             * @var $producer IRequest
             */

            $producer = new $producerCLassName($_jobTicketList);

            $producer->execute();
        }

        return true;
    }
}