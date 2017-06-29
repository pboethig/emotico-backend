<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 25.12.16
 * Time: 02:16
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\InDesign\Ticket\Indb;

use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Commands\DocumentExportJPG;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Types\AdditionalData;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Types\Response;
use Mittax\MediaConverterBundle\Ticket\ITicketBuilder;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\ThumbnailTicketAbstract;

/**
 * Class Thumbnail
 * @package Mittax\MediaConverterBundle\ThumbnailTicket
 */
class Generator extends ThumbnailTicketAbstract
{

    public function __construct(ITicketBuilder $builder)
    {
        parent::__construct($builder);
    }

    public function postBuild()
    {
        $classname = "Book.ExportJPG";

        $baseName = $this->storageItem->getBasename();

        $uuid = $this->storageItem->getUuid();

        $dirname = $this->storageItem->getDirname();

        $storageFolder = explode("/", $dirname)[0];

        $documentFolderPath = Config::getInDesignServerRoot()."\\" . $storageFolder;

        $additionalData = [new AdditionalData($classname,"pageThumbnailPaths")];

        $clientEvent = "indesignserver.lowres.created";

        $response = new Response($uuid, Config::getInDesignServerWebhookClientUrls() , $additionalData, $clientEvent);
        /***
         * Build command
         */
        $extension = $this->storageItem->getExtension();

        $exportFolderPath = Config::getInDesignServerExportPath();

        $commands = [new DocumentExportJPG($classname, $uuid, $extension, $baseName, $exportFolderPath)];

        $ticket = new \Mittax\MediaConverterBundle\Ticket\InDesignServer\Ticket($uuid, $documentFolderPath ,$response, $commands);

        return $ticket;
    }
}