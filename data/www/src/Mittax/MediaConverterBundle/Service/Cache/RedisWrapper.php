<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 19.12.16
 * Time: 18:41
 */

namespace Mittax\MediaConverterBundle\Service\Cache;

use \Predis\Client;

class RedisWrapper
{

    /**
     * @var Client
     */
    private static $_connectionPool = false;

    /**
     * @return Client
     */
    public static function getCacheClient()
    {
        if (self::$_connectionPool)
        {
            $cacheClient = self::$_connectionPool;
        }
        else
        {
            /**
             * @todo: get redis url from config
             */
            $cacheClient = new Client('tcp://redis:6379');

            self::$_connectionPool = $cacheClient;
        }

        return $cacheClient;
    }
}