<?php
namespace Entity\WargamingAPI\WoT;

use Entity\WargamingAPI\WoT\CBase;

class CAccount extends CBase
{
    protected static $method_block = 'account';

    private $_AuthInfo = [
        'access_token' => '',
        'nickname' => '',
        'account_id' => '',
    ];

    /**
     * @param array $AuthInfo Authoriztion info
     */
    public function __construct(array $AuthInfo) {
        $this->_AuthInfo = [
            'access_token' => $AuthInfo['access_token'],
            'nickname' => $AuthInfo['nickname'],
            'account_id' => $AuthInfo['account_id'],
        ];
    }

    /**
     * @param string $search Player nickname
     * 
     * @return array
     */
    public static function getPlayer(string $search)
    {
        $method_name = 'list';
        $options = [
            'search' => $search,
            'type' => 'exact',
        ];
        $params = static::_prepareParams($options);
        $response = static::_api($method_name, $params);

        $APIrespone = json_decode($response, true);
        if ($APIrespone['status'] === 'ok') {
            $result = $APIrespone['data'][0];
        } else {
            $error = [
                'message' => $authRespone['error']['message'],
                'field' => $authRespone['error']['field'],
                'value' => $authRespone['error']['value'],
                'field' => $authRespone['error']['code'],
            ];
            throw new \Core\Exceptions\CAPIException($error);
        }

        return $result;
    }

    /**
     * @param string $search Player nickname
     * 
     * @return array
     */
    public function getCommonInfo()
    {
        $method_name = 'info';
        $options = [
            'access_token' => $this->_AuthInfo['access_token'],
            'account_id' => $this->_AuthInfo['account_id'],
            'fields' => 'clan_id,client_language,created_at,global_rating,last_battle_time,logout_at,updated_at',
        ];
        $params = static::_prepareParams($options);
        $response = static::_api($method_name, $params);

        $APIrespone = json_decode($response, true);
        if ($APIrespone['status'] === 'ok') {
            $result = reset($APIrespone['data']);
        } else {
            $error = [
                'message' => $authRespone['error']['message'],
                'field' => $authRespone['error']['field'],
                'value' => $authRespone['error']['value'],
                'field' => $authRespone['error']['code'],
            ];
            throw new \Core\Exceptions\CAPIException($error);
        }

        return $result;
    }
}
