<?php
namespace Entity\WargamingAPI\WoT;

use Core\CApplication;
use Service\Network\CcURL;
use \Core\Exceptions\CAPIException;

abstract class CBase
{
    protected static $protocol = 'https';
    protected static $server = 'api.worldoftanks.ru';
    protected static $API_name = 'wot';
    protected static $method_block = '';
    protected $_AuthInfo = [
        'access_token' => '',
        'nickname' => '',
        'account_id' => '',
    ];

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
            throw new CAPIException($error);
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

    /**
     * @param string $apiResponse Response from Wargaming API
     *
     * @return array
     */
    protected static function _handleResponse(string $apiResponse)
    {
        $responseArray = json_decode($apiResponse, true);
        if ($responseArray['status'] === 'ok') {
            $result = $responseArray['data'];
        } else {
            $error = [
                'message' => $responseArray['error']['message'],
                'field' => $responseArray['error']['field'],
                'value' => $responseArray['error']['value'],
                'field' => $responseArray['error']['code'],
            ];
            throw new CAPIException($error);
        }

        return $result;
    }

    /**
     * @param string $apiResponse Response from Wargaming API
     *
     * @return array
     */
    protected static function _makeRequest(string $method_name, array $options)
    {
        $params = static::_prepareParams($options);
        $response = static::_api($method_name, $params);
        $result = static::_handleResponse($response);

        return $result;
    }

    /**
     * @param array $fields Fields request array
     *
     * @return array
     */
    protected function _prepareFields(array $fields = [], array $extra = [])
    {
        $result = [
            'access_token' => $this->_AuthInfo['access_token'],
            'account_id' => $this->_AuthInfo['account_id'],
            'fields' => implode(',', $fields),
            'extra' => (count($extra) > 0)
                ? implode(',', $extra)
                : '',
        ];
        
        return $result;
    }

    /**
     * @param array $AuthInfo Authoriztion info
     */
    public function __construct(array $AuthInfo)
    {
        $this->_AuthInfo = [
            'access_token' => $AuthInfo['access_token'],
            'nickname' => $AuthInfo['nickname'],
            'account_id' => $AuthInfo['account_id'],
        ];
    }
}
