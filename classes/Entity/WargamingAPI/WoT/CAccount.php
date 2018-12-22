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
    public function __construct(array $AuthInfo)
    {
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
        $handledResponse = static::_makeRequest($method_name, $options);
        $result = reset($handledResponse);

        return $result;
    }

    /**
     * @param int $account_id Player's account id
     *
     * @return array
     */
    public function getCommonInfo(int $account_id = null)
    {
        $method_name = 'info';
        $fields = [
            'clan_id',
            'client_language',
            'created_at',
            'global_rating',
            'last_battle_time',
            'logout_at',
            'updated_at',
        ];
        $options = [
            'access_token' => (is_null($account_id))
                ? $this->_AuthInfo['access_token']
                : '',
            'account_id' => (is_null($account_id))
                ? $this->_AuthInfo['account_id']
                : $account_id,
            'fields' => implode(',', $fields),
        ];
        $handledResponse = static::_makeRequest($method_name, $options);
        $result = reset($handledResponse);

        return $result;
    }

    /**
     * @param int $account_id Player's account id
     *
     * @return array
     */
    public function getEconomics(int $account_id = null)
    {
        $method_name = 'info';
        $fields = [
            'private.bonds',
            'private.credits',
            'private.free_xp',
            'private.gold',
            'private.is_premium',
            'private.premium_expires_at',
        ];
        $options = [
            'access_token' => (is_null($account_id))
                ? $this->_AuthInfo['access_token']
                : '',
            'account_id' => (is_null($account_id))
                ? $this->_AuthInfo['account_id']
                : $account_id,
            'fields' => implode(',', $fields),
        ];
        $handledResponse = static::_makeRequest($method_name, $options);
        $result = reset($handledResponse)['private'];

        return $result;
    }
}
