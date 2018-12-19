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

    public function __construct(Entity\WargamingAPI\WoT\CAuth $AuthInfo) {
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
}
