<?php
namespace Entity\WargamingAPI\WoT;

use Core\CApplication;
use Service\Network\CcURL;

abstract class CBase
{
    protected $protocol = 'https';
    protected $server = 'api.worldoftanks.ru';
    protected $API_name = 'wot';
    protected $method_block = '';

    /**
     * @param string $method_name Method for execution
     * @param array $params Parameters for method
     *
     * @return mixed
     */
    protected function _api(string $method_name, array $params)
    {
        $url = "{$this->protocol}://{$this->server}/{$this->API_name}/{$this->method_block}/{$method_name}/";
        $result = CcURL::post($url, $params);

        return $result;
    }

    /**
     * @param array $options Options for method parameters
     *
     * @return array
     */
    protected function _prepareParams(array $options)
    {
        global $APPLICATION;
        $defaults = [
            'application_id' => CApplication::getConfiguration('APP_ID'),
            'language' => CApplication::getConfiguration('LANGUAGE'),
        ];

        $result = array_merge($defaults, $options);
        return $result;
    }
}
