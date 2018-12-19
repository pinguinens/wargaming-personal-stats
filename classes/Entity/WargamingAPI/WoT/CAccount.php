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

    public function __construct(array $AuthInfo) {
        $this->_AuthInfo = [
            'access_token' => $AuthInfo['access_token'],
            'nickname' => $AuthInfo['nickname'],
            'account_id' => $AuthInfo['account_id'],
        ];
    }

    /**
     * @param string $search Player nickname
     */
    public static function getPlayer(string $search)
    {
        $method_name = 'list';
        $options = [
            'search' => $search,
            'type' => 'exact',
        ];
        $params = static::_prepareParams($options);

        $result = static::_api($method_name, $params);

        return $result;
    }

    /**
     * @param string $search Player nickname
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

        $result = static::_api($method_name, $params);

        return $result;
    }
}
