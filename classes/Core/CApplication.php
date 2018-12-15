<?php
namespace Core;

/**
 * Main application Singleton class
 */
final class CApplication
{
    private static $instance;
    private  $_CONFIGURATION = [];

    /**
     * @return CApplication
     */
    public static function getInstance(): CApplication
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->_CONFIGURATION = $this->_loadConfiguration();
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    private function _loadConfiguration()
    {
        include_once(_DOCUMENT_ROOT_ .'/core/config.php');
        $_APP_['DOCUMENT_ROOT'] = _DOCUMENT_ROOT_;

        $result = $_APP_;

        return $result;
    }

    /**
     * @param string $prop Property
     * 
     * @return mixed
     */
    public function getConfiguration(string $prop = null) 
    {
        if(is_null($prop)) {
            $result = $this->_CONFIGURATION;
        } else {
            $result = $this->_CONFIGURATION[$prop];
        }

        return $result;
    }
}
