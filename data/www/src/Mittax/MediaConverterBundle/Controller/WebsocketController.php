<?php

namespace Mittax\MediaConverterBundle\Controller;

use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\RabbitMQBundle\Service\Api\Queue;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Process\Process;


class WebsocketController extends AbstractController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="starts the websocket server",
     *  section = "MediaConverter",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="retriving failed"
     *  },
     * )
     * @Method("GET")
     * @Route("/websocket/start")
     */
    public function startAction(Request $request)
    {
        $response = $this->getCoresResponse();

        try
        {
            $processes = new Process("php /var/www/app/console gos:websocket:server");

            $processes->setTimeout(81400);

            $processes->run();

            $response->setStatusCode(200);

            $response->setContent('{"message":"success"}');

            return $response;

        }catch (\Exception $ex)
        {
            $response->setContent($ex->getMessage());

            $response->setStatusCode(500);

            return $response;
        }
    }


}
