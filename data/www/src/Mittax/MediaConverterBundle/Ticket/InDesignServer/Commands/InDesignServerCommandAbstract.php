<?php

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 24.05.17
 * Time: 19:34
 */
namespace Mittax\MediaConverterBundle\Ticket\InDesignServer\Commands;

/**
 * Class DocumentExportJPG
 * @package Mittax\MediaConverterBundle\Ticket\InDesignServer
 */
abstract class InDesignServerCommandAbstract implements IInDesignServerCommand
{
    /**
     * @var string
     */
    public $classname = "";

    /**
     * @var string
     */
    public $uuid = "";

    /**
     * @var string
     */
    public $extension="";
    /**
     * @var string
     */
    public $version = "";

    /**
     * InDesignServerCommandAbstract constructor.
     * @param string $classname
     * @param string $uuid
     * @param string $extension
     * @param string $version
     */
    public function __construct(string $classname, string $uuid,string $extension, string $version)
    {
        $this->classname = $classname;

        $this->uuid = $uuid;

        $this->extension = $extension;

        $this->version = $version;
    }
}