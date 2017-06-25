<?php

namespace Mittax\MediaConverterBundle\Controller;

use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Service\System\Config;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class InDesignServerController extends Controller
{
    use ContainerAwareTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="ping indesign server",
     *  section = "InDesignServer",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="trigger failed"
     *  },
     * )
     *
     * @Route("/indesignserver/ping")
     * @Method({"GET"})
     */
    public function ping()
    {
        $response = new Response('file uploaded');

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers', 'Cache-Control, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');

        $response->setContent(json_encode(['result' => Config::pingInDesignServer(), 'IP'=>Config::getInDesignServerIp()]));

        return $response;
    }
}
