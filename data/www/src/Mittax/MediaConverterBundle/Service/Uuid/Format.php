<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 21:09
 */

namespace Mittax\MediaConverterBundle\Service\Uuid;

class Format
{
    /**
     * @var Format
     */
    private $item;

    /**
     * Format constructor.
     * @param \Mittax\MediaConverterBundle\ValueObjects\Format $item
     */
    public function __construct(\Mittax\MediaConverterBundle\ValueObjects\Format $item)
    {
        $this->item = $item;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return md5($this->item->getMimeType().$this->item->getName());
    }
}