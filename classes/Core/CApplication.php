<?php
namespace Core;

use Core\Exceptions\CconfigurationException;

/**
 * Main application Singleton class
 */
final class CApplication
{
    private static $instance;
    private static $_CONFIGURATION = [];

    /**
     * @return CApplication
     */
    public static function makeInstance(): CApplication
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
        static::$_CONFIGURATION = static::_loadConfiguration();
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    /**
     * @return array
     */
    private static function _loadConfiguration()
    {
        $configFilePath = _DOCUMENT_ROOT_ .'/core/config.php';
        if (file_exists($configFilePath)) {
            include_once($configFilePath);
            $_APP_['DOCUMENT_ROOT'] = _DOCUMENT_ROOT_;

            $result = $_APP_;

            return $result;
        } else {
            $error = [
                'message'           => 'Configuration file does not exist.',
                'configFilePath'    => $configFilePath,
                'code'              => 1000,
            ];
            throw new CConfigurationException($error);
        }
    }

    /**
     * @param string $prop Property
     *
     * @return mixed
     */
    public static function getConfiguration(string $prop = null)
    {
        if (is_null($prop)) {
            $result = static::$_CONFIGURATION;
        } else {
            if (array_key_exists($prop, static::$_CONFIGURATION)) {
                $result = static::$_CONFIGURATION[$prop];
            } else {
                $error = [
                    'message'   => 'Configuration property does not exist.',
                    'prop'      => $prop,
                    'code'      => 1001,
                ];
                throw new CConfigurationException($error);
            }
        }

        return $result;
    }
}
