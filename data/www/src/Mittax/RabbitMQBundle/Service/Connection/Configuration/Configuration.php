<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.12.16
 * Time: 13:45
 */

namespace Mittax\RabbitMQBundle\Service\Connection\Configuration;


use Mittax\RabbitMQBundle\Exception\ConfigPropertyNotFoundException;
use Mittax\RabbitMQBundle\Exception\LocalCertPathDoesNotExistsException;
use Mittax\RabbitMQBundle\Exception\SSLCAFilePathDoesNotExistsException;

/**
 * Class Configuration
 * @package Mittax\RabbitMQBundle\Service\Connection
 */
class Configuration
{
    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_host;

    /**
     * @var string
     */
    private $_port;

    /**
     * @var int
     */
    private $_apiport = 15672;

    /**
     * @var string
     */
    private $_ssl_cafilepath;

    /**
     * @var string
     */
    private $_ssl_localcertpath;

    /**
     * @var boolean
     */
    private $_ssl_verify_peer = false;

    /**
     * @var string
     */
    private $_vhost;

    /**
     * @var string
     */
    private $_username;

    /**
     * @var string
     */
    private $_password;

    /**
     * @var string
     */
    private $_ssl_context;

    /**
     * @var bool
     */
    private $_debug = true;

    /**
     * @var bool
     */
    private $_lazy = true;

    /**
     * @var int
     */
    private $_connection_timeout = 3;

    /**
     * @var int
     */
    private $_read_write_timeout = 30;

    /**
     * @var bool
     */
    private $_keepalive = true;

    /**
     * @var int
     */
    private $_heartbeat = 0;

    /**
     * @var bool
     */
    private $_use_socket = true;

    /**
     * @var array
     */
    private $_rawData;

    /**
     * @var bool
     */
    private $_insist = false;

    /**
     * @var string
     */
    private $_login_method = 'AMQPLAIN';

    /**
     * @var null
     */
    private $_login_response = null;

    /**
     * @var string
     */
    private $_locale = 'en_US';

    /**
     * @var resource
     */
    private $_streamContext;

    /**
     * Configuration constructor.
     * @param $connectionName
     * @param array $rawData
     */
    public function __construct($connectionName, Array $rawData)
    {
        $this->_rawData = $rawData;

        $this->_name = $connectionName;

        $this->_setProperties($rawData);
    }


    private function _setProperties(Array $rawData) : bool
    {
        foreach ($rawData as $propertyName => $value)
        {
            $propertyName = '_' . $propertyName;

            if (!property_exists($this, $propertyName))
            {
                throw  new ConfigPropertyNotFoundException('Property ' . $propertyName . ' not found exception');
            }

            $this->{$propertyName} = $value;
        }

        return true;
    }

    /**
     * @return resource
     */
    public function getStreamContext()
    {
        return $this->_streamContext;
    }

    /**
     * @return string
     */
    public function getSSL_CA_Filepath()
    {
        return $this->_ssl_cafilepath;
    }

    /**
     * @return string
     */
    public function getSSLLocalCertpath()
    {
        return $this->_ssl_localcertpath;
    }

    /**
     * @return boolean
     */
    public function isSSLVerifyPeer()
    {
        return $this->_ssl_verify_peer;
    }

    /**
     * @return int
     */
    public function getReadWriteTimeout()
    {
        return $this->_read_write_timeout;
    }

    /**
     * @return array
     */
    public function getRawData()
    {
        return $this->_rawData;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->_host;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->_port;
    }

    /**
     * @return string
     */
    public function getApiPort()
    {
        return $this->_apiport;
    }

    /**
     * @return string
     */
    public function getVhost()
    {
        return $this->_vhost;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @return string
     */
    public function getSslContext()
    {
        if ($this->_ssl_context == false)
        {
             $this->_ssl_context =
                 [ 'ssl_context'=>
                    [
                        'verify_peer' => false,
                    ]
                ];
        }

        return $this->_ssl_context;
    }

    /**
     * @return array|null
     */
    public function getSSLOptionArray()
    {
        if ($this->getSSL_CA_Filepath() && $this->isSSLVerifyPeer() && $this->getSSLLocalCertpath())
        {
            if (!file_exists($this->getSSL_CA_Filepath()))
            {
                throw new SSLCAFilePathDoesNotExistsException('CA filepath: ' . $this->getSSL_CA_Filepath() . 'does not exists.');
            }

            if (!file_exists($this->getSSLLocalCertpath()))
            {
                throw new LocalCertPathDoesNotExistsException('Local cert filepath: ' . $this->getSSLLocalCertpath() . 'does not exists.');
            }

            return [
                'cafile' => $this->getSSL_CA_Filepath(),
                'local_cert' => $this->getSSLLocalCertpath(),
                'verify_peer' => $this->isSSLVerifyPeer()
            ];
        }

        return [];
    }

    /**
     * @return boolean
     */
    public function isInsist()
    {
        return $this->_insist;
    }

    /**
     * @return string
     */
    public function getLoginMethod()
    {
        return $this->_login_method;
    }

    /**
     * @return null
     */
    public function getLoginResponse()
    {
        return $this->_login_response;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->_locale;
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->_debug;
    }

    /**
     * @return boolean
     */
    public function isLazy()
    {
        return $this->_lazy;
    }

    /**
     * @return int
     */
    public function getConnectionTimeout()
    {
        return $this->_connection_timeout;
    }

    /**
     * @return boolean
     */
    public function isKeepAlive()
    {
        return $this->_keepalive;
    }

    /**
     * @return int
     */
    public function getHartBeat()
    {
        return $this->_heartbeat;
    }

    /**
     * @return boolean
     */
    public function isUseSockets()
    {
        return $this->_use_socket;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $ssl_cafilepath
     */
    public function setSslCafilepath($ssl_cafilepath)
    {
        $this->_ssl_cafilepath = $ssl_cafilepath;
    }

    /**
     * @param string $ssl_localcertpath
     */
    public function setSslLocalcertpath($ssl_localcertpath)
    {
        $this->_ssl_localcertpath = $ssl_localcertpath;
    }

    /**
     * @param boolean $ssl_verify_peer
     */
    public function setSslVerifyPeer($ssl_verify_peer)
    {
        $this->_ssl_verify_peer = $ssl_verify_peer;
    }

    /**
     * @param boolean $lazy
     */
    public function setLazy($lazy)
    {
        $this->_lazy = $lazy;
    }

    /**
     * @param boolean $use_socket
     */
    public function setUseSocket($use_socket)
    {
        $this->_use_socket = $use_socket;
    }
}