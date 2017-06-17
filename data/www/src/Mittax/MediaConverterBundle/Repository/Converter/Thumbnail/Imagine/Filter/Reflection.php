<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 21.12.16
 * Time: 23:08
 */

namespace Mittax\MessageBundle\Repository\Converter\Thumbnail\Imagine;

/**
 * Class ReflectionFilter
 * @package Mittax\MessageBundle\Repository\Converter\Thumbnail\Imagine
 */
class ReflectionFilter implements \Imagine\Filter\FilterInterface
{
    private $imagine;

    public function __construct(\Imagine\Image\ImagineInterface $imagine)
    {
        $this->imagine = $imagine;
    }

    public function apply(\Imagine\Image\ImageInterface $image)
    {
        $size       = $image->getSize();
        $canvas     = new \Imagine\Image\Box($size->getWidth(), $size->getHeight() * 2);
        $reflection = $image->copy()
            ->flipVertically()
            ->applyMask($this->getTransparencyMask($image->palette(), $size))
        ;

        return $this->imagine->create($canvas, $image->palette()->color('fff', 100))
            ->paste($image, new \Imagine\Image\Point(0, 0))
            ->paste($reflection, new \Imagine\Image\Point(0, $size->getHeight()));
    }

    private function getTransparencyMask(\Imagine\Image\Palette\PaletteInterface $palette, \Imagine\Image\BoxInterface $size)
    {
        $white = $palette->color('fff');
        $fill  = new \Imagine\Image\Fill\Gradient\Vertical(
            $size->getHeight(),
            $white->darken(127),
            $white
        );

        return $this->imagine->create($size)
            ->fill($fill)
            ;
    }
}