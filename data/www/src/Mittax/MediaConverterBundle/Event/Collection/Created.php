<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Collection;

use Mittax\ObjectCollection\ICollection;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Thumbnail
 */
class Created extends Event
{
    const NAME = 'collection.created';

    protected $_collection;

    /**
     * Created constructor.
     * @param ICollection $collection
     */
    public function __construct(ICollection &$collection)
    {
        $this->_collection = $collection;
    }

    /**
     * @return ICollection
     */
    public function getCollection()
    {
        return $this->_collection;
    }
}