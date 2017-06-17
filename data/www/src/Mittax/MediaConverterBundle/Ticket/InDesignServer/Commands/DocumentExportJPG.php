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
class DocumentExportJPG extends InDesignServerCommandAbstract
{
    /**
     * @var string
     */
    public $exportFolderPath;

    /**
     * DocumentExportJPG constructor.
     * @param string $classname
     * @param string $uuid
     * @param string $version
     * @param string $exportFolderPath
     */
    public function __construct(string $classname, string $uuid,string $extension, string $version, string $exportFolderPath)
    {
        parent::__construct($classname,$uuid,$extension, $version);

        $this->exportFolderPath = $exportFolderPath;
    }
}