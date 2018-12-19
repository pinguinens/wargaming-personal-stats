<?php
namespace Entity\WargamingAPI\WoT;

use Core\CApplication;
use Service\Network\CcURL;

abstract class CBase
{
    protected static $protocol = 'https';
    protected static $server = 'api.worldoftanks.ru';
    protected static $API_name = 'wot';
    protected static $method_block = '';

    /**
     * @param string $method_name Method for execution
     * @param array $params Parameters for method
     *
     * @return mixed
     */
    protected static function _api(string $method_name, array $params)
    {
        $url = static::$protocol.'://'.static::$server.'/'.static::$API_name.'/'.static::$method_block.'/'.$method_name.'/';
        $response = CcURL::post($url, $params);

        if (is_null($response)) {
            $error = [
                'message'   => 'API request was failed.',
                'code'      => 2003,
            ];
            throw new \Core\Exceptions\CAPIException($error);
        } else {
            $result = $response;
        }

        return $result;
    }

    /**
     * @param array $options Options for method parameters
     *
     * @return array
     */
    protected static function _prepareParams(array $options)
    {
        $defaults = [
            'application_id' => CApplication::getConfiguration('APP_ID'),
            'language' => CApplication::getConfiguration('LANGUAGE'),
        ];

        $result = array_merge($defaults, $options);
        return $result;
    }
}
