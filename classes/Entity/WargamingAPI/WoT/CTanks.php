<?php
namespace Entity\WargamingAPI\WoT;

use Entity\WargamingAPI\WoT\CBase;

class CTanks extends CBase
{
    protected static $method_block = 'tanks';

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
        $result = reset($handledResponse)[0]['all'];

        return $result;
    }
}
