<?php
namespace Mittax\MediaConverterBundle\Service\InDesignServer\Document;
use Mittax\MediaConverterBundle\Service\InDesignServer\PathMapping;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Types\Response;

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 27.05.17
 * Time: 16:54
 */
class Thumbnail
{
    /**
     * @param Response $indesignServerResponse
     * @return array
     */
    public static function buildThumbnailList(Response $indesignServerResponse)
    {
        $thumbNailList = [];

        $pathMappingService = new PathMapping();

        foreach ($indesignServerResponse->additionalData as $item)
        {
            if(strpos($item->Key,'pageThumbnailPaths')>-1)
            {
                foreach ($item->Value as $sharePath)
                {
                    $thumbNailList[] = $pathMappingService->convertNetworkPathToUrl($sharePath);
                }
            }
        }

        return $thumbNailList;
    }
}