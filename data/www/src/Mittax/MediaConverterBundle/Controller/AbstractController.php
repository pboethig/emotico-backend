<?php

namespace Mittax\MediaConverterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractController
 * @package Mittax\MediaConverterBundle\Controller
 */
class AbstractController extends Controller
{
    use ContainerAwareTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @return Response
     */
    protected function getCoresResponse()
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Credentials', 'false');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Cache-Control, Accept, Origin, X-Session-ID');
        $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,HEAD,DELETE,TRACE,COPY,LOCK,MKCOL,MOVE,PROPFIND,PROPPATCH,UNLOCK,REPORT,MKACTIVITY,CHECKOUT,MERGE,M-SEARCH,NOTIFY,SUBSCRIBE,UNSUBSCRIBE,PATCH,OPTIONS');

        return $response;
    }
}