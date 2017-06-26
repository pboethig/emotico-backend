<?php

namespace Mittax\MediaConverterBundle\Service\WebHook;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Client
 * @package Mittax\MediaConverterBundle\Service\WebHook
 */
class Client
{
    /**
     * @param array $message
     * @param array $clientUrls
     * @return Response[]
     */
    public function call(array $message, array $clientUrls) : array
    {
        $responses = [];

        foreach ($clientUrls as $clientUrl)
        {
            $client = new \GuzzleHttp\Client([
                'headers' => [ 'Content-Type' => 'application/json' ]
            ]);

            file_put_contents("/var/www/text333.log", json_encode($message));

            $response = $client->post($clientUrl, ['body' => json_encode($message)]);

            $responses[] = $response;
        }

        return $responses;
    }
}