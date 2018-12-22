<?php
namespace Entity\WargamingAPI\WoT;

use Entity\WargamingAPI\WoT\CBase;

class CTanks extends CBase
{
    protected static $method_block = 'tanks';

    private $_AuthInfo = [
        'access_token' => '',
        'nickname' => '',
        'account_id' => '',
    ];

    /**
     * @param array $fields Fields request array
     *
     * @return array
     */
    private function _prepareFields(array $fields = [], array $extra = [])
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

    /**
     * @return array
     */
    public function getTankStatistics(int $tank_id = null)
    {
        $method_name = 'stats';
        $fields = [
            'all',
        ];
        $options = $this->_prepareFields($fields);
        $options['tank_id'] = (is_null($tank_id))
            ? ''
            : $tank_id;
        $handledResponse = static::_makeRequest($method_name, $options);
        $result = reset($handledResponse);

        return $handledResponse;
    }
}
