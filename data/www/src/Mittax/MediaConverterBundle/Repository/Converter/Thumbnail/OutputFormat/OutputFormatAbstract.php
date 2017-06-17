<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 22.12.16
 * Time: 00:11
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat;

use Mittax\MediaConverterBundle\Exception\OutputFormatNotExistException;
use Mittax\MediaConverterBundle\Traits\Creation\Construct;
use Mittax\MediaConverterBundle\ValueObjects\Size;

/**
 * Class OutputFormatAbstract
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat
 */
abstract class OutputFormatAbstract implements IOutputFormat
{
    /**
     * @var string
     */
    private $_format = '';

    /**
     * @var array
     */
    private $_quality = [];
    /**
     * @var array
     */
    private $_sizes;

    /**
     * @var string
     */
    private $_mode; // or outbound

    /**
     *
    const RESOLUTION_PIXELSPERINCH = 'ppi';
    const RESOLUTION_PIXELSPERCENTIMETER = 'ppc';

    const INTERLACE_NONE = 'none';
    const INTERLACE_LINE = 'line';
    const INTERLACE_PLANE = 'plane';
    const INTERLACE_PARTITION = 'partition';

    const FILTER_UNDEFINED = 'undefined';
    const FILTER_POINT = 'point';
    const FILTER_BOX = 'box';
    const FILTER_TRIANGLE = 'triangle';
    const FILTER_HERMITE = 'hermite';
    const FILTER_HANNING = 'hanning';
    const FILTER_HAMMING = 'hamming';
    const FILTER_BLACKMAN = 'blackman';
    const FILTER_GAUSSIAN = 'gaussian';
    const FILTER_QUADRATIC = 'quadratic';
    const FILTER_CUBIC = 'cubic';
    const FILTER_CATROM = 'catrom';
    const FILTER_MITCHELL = 'mitchell';
    const FILTER_LANCZOS = 'lanczos';
    const FILTER_BESSEL = 'bessel';
    const FILTER_SINC = 'sinc';
     *
     * @var string
     */
    private $_filter;

    /**
     * @var \Imagine\Filter\FilterInterface
     */
    private $_additionalFilter;

    use Construct;

    /**
     * Jpg constructor.
     * @param array $rawData
     */
    public function __construct(Array $rawData)
    {
        $this->validate($rawData);

        $this->constructByKeyValue($rawData);

        $this->setSizes(Size::fromSizesArray($rawData['sizes']));
    }

    /**
     * @param array $sizes
     */
    public function setSizes($sizes)
    {
        $this->_sizes = $sizes;
    }

    /**
     * @param array $rawData
     * @return bool
     */
    public function validate(Array $rawData) : bool
    {
        if (!isset($rawData['format']))
        {
            throw new OutputFormatNotExistException('No format set in config');
        }

        return true;
    }

    /**
     * @return string
     */
    public function getFormat() : string
    {
        return $this->_format;
    }

    /**
     * @return array
     */
    public function getQuality() : Array
    {
        return $this->_quality;
    }

    /**
     * @return string
     */
    public function getMode() : string
    {
        return $this->_mode;
    }

    /**
     * @return string
     */
    public function getFilter() : string
    {
        return $this->_filter;
    }

    /**
     * @return string
     */
    public function getAdditionalFilter() : string
    {
        return $this->_additionalFilter;
    }

    /**
     * @return array
     */
    public function getSizes() : Array
    {
        return $this->_sizes;
    }
}