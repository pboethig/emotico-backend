<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 24.05.17
 * Time: 19:34
 */
namespace Mittax\MediaConverterBundle\Ticket\InDesignServer;

use JMS\Serializer\SerializationContext;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Types\Response;

/**
 * Class DocumentExportJPG
 * @package Mittax\MediaConverterBundle\Ticket\InDesignServer
 */
class Ticket
{
    /**
     * @var string
     */
    public $id="";

    /**
     * @var string
     */
    public $documentFolderPath="";

    /**
     * @var Response
     */
    public $response;

    /**
     * @var \stdClass
     */
    public $commands;

    /**
     * DocumentExportJPG constructor.
     * @param string $id
     * @param string $documentFolderPath
     * @param Response $response
     */
    public function __construct(string $id, string $documentFolderPath, Response $response, array $commands)
    {
        $this->id = $id;

        $this->documentFolderPath = $documentFolderPath;

        $this->response = $response;

        $this->commands = $commands;
    }

    /**
     * @return mixed
     */
    public function toJson()
    {
        $serializer = \AppKernel::getContainerStatic()->get("serializer");

        $json = $serializer->serialize($this, 'json', SerializationContext::create()->setVersion('0.0.1'));

        return $json;
    }
}