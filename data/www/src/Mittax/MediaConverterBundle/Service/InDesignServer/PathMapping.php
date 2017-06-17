<?php
namespace Mittax\MediaConverterBundle\Service\InDesignServer;

use Mittax\MediaConverterBundle\Ticket\InDesignServer\Types\Response;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use Mittax\MediaConverterBundle\Service\System\Config;
use Symfony\Component\EventDispatcher\EventDispatcher;
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 26.05.17
 * Time: 21:16
 */
class PathMapping
{
    /**
     * @param $indesignServerSharePath
     * @return mixed
     */
    public function convertNetworkPathToUrl(string $indesignServerSharePath): string
    {
        $path = preg_replace('~\{4,}~', '\\', $indesignServerSharePath);

        $publicUrl = str_replace(Config::getInDesignServerRoot(), Config::getPublicStorageUrl(), $path);

        $publicUrl = str_replace("\\","/", $publicUrl);

        return $publicUrl;
    }

    /**
     * @param string $jsonResponse
     * @return Response
     */
    public function buildTypedResponseFromJson(string $jsonResponse) : Response
    {
        $responseObject = json_decode($jsonResponse);

        $response = new Response(
            $responseObject->ticketId,
            $responseObject->urls,
            $responseObject->additionalData,
            $responseObject->clientEvent,
            $responseObject->status,
            $responseObject->ticket,
            $responseObject->errors
        );

        return $response;
    }
}