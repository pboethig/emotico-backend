<?php

namespace Mittax\MediaConverterBundle\Controller;

use Mittax\MediaConverterBundle\Service\System\Config;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ConfigController
 * @package Mittax\MediaConverterBundle\Controller
 */
class ConfigController extends AbstractController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="reads mediaconverter config",
     *  section = "MediaConverter",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="retriving failed"
     *  },
     * )
     * @Method("GET")
     * @Route("/config/get")
     */
    public function getConfigAction(Request $request)
    {
        $response = $this->getCoresResponse();

        try
        {
            $mediaconverterConfig = Config::getMediaConverterConfig();

            $response->setContent(json_encode($mediaconverterConfig));

            $response->setStatusCode(200);

            return $response;

        }catch (\Exception $ex)
        {
            $response->setContent('{"error":"'.$ex->getMessage().'"}');

            $response->setStatusCode(500);

            return $response;
        }
    }
}
