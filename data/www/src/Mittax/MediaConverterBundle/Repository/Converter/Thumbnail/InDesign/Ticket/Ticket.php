<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 25.12.16
 * Time: 02:16
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\InDesign\Ticket;

use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Commands\DocumentExportJPG;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Types\AdditionalData;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Types\Response;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\ThumbnailTicketAbstract;

/**
 * Class Thumbnail
 * @package Mittax\MediaConverterBundle\ThumbnailTicket
 */
class Ticket extends ThumbnailTicketAbstract
{

    public function postBuild()
    {
        $baseName = rtrim(str_replace($this->storageItem->getExtension(),'', $this->storageItem->getBasename()),'.');

        $id = Upload::md5($baseName . "." . $this->storageItem->getExtension());

        $dirname = $this->storageItem->getDirname();

        $storageFolder = explode("/", $dirname)[0];

        $documentFolderPath = Config::getInDesignServerRoot()."\\" . $storageFolder;

        $additionalData = [new AdditionalData("Document.ExportJPG","pageThumbnailPaths")];

        $clientEvent = "indesignserver.lowres.created";

        $response = new Response($id, Config::getInDesignServerWebhookClientUrls() , $additionalData, $clientEvent);

        /***
         * Build command
         */
        $classname = "Document.ExportJPG";

        $uuid = $this->getStorageItem()->getUuid();

        $extension = $this->storageItem->getExtension();

        $exportFolderPath = Config::getInDesignServerExportPath();

        $commands = [new DocumentExportJPG($classname, $uuid, $extension, $baseName, $exportFolderPath)];

        $ticket = new \Mittax\MediaConverterBundle\Ticket\InDesignServer\Ticket($id, $documentFolderPath ,$response, $commands);

        return $ticket;
    }

    public function serialize() : string
    {
        $json = $this->postBuild()->toJson();

        return $json;
    }
}