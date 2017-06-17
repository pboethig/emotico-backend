<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Thumbnail;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class CollectionCreated
 * @package Mittax\MediaConverterBundle\Event\Thumbnail
 */
class CollectionCreated extends Event
{
    const NAME = 'thumbnail.collection.created';

    /**
     * @var \Mittax\MediaConverterBundle\Collection\Thumbnail
     */
    protected $_collection;

    /**
     * CollectionCreated constructor.
     * @param \Mittax\MediaConverterBundle\Collection\Thumbnail $collection
     */
    public function __construct(\Mittax\MediaConverterBundle\Collection\Thumbnail &$collection)
    {
        $this->_collection = $collection;
    }

    /**
     * @return \Mittax\MediaConverterBundle\Collection\Thumbnail
     */
    public function getCollection()
    {
        return $this->_collection;
    }
}