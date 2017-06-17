<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 21:09
 */

namespace Mittax\MediaConverterBundle\Service\Uuid;

/**
 * Class StorageItem
 * @package Mittax\MediaConverterBundle\Service\Uuid
 */
class StorageItem
{
    /**
     * @var string
     */
    public $uuid;

    /**
     * StorageItem constructor.
     * @param array $rawData
     */
    public function __construct(Array $rawData)
    {
        $this->uuid = md5($rawData['filename'] . $rawData['size'] . $rawData['type']);
    }
}