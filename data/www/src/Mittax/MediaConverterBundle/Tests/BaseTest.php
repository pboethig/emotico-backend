<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 18.12.16
 * Time: 20:26
 */

namespace Mittax\MediaConverterBundle\Tests;


use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BaseTest extends KernelTestCase
{

    /**
     * @var \Blackfire\Client
     */
    protected static $_blackfire;

    public function setUp()
    {
        parent::setUp();
    }

    public static function startBlackFire(string $profileTitle)
    {

        self::$_blackfire = \Mittax\MediaConverterBundle\Service\BlackFire\Client::getClient();

        $probe = self::$_blackfire->createProbe(null, false);

        $probe->enable();

        return $probe;
    }

    /**
     * @param \Blackfire\Probe $probe
     * @return \Blackfire\Profile
     */
    public static function stopBlackFire(\Blackfire\Probe $probe)
    {
        $probe->disable();

        $profile = self::$_blackfire->endProbe($probe);

        return $profile;
    }

}