<?php
namespace Entity\WargamingAPI\WoT;

use Entity\WargamingAPI\WoT\CBase;

class CAccount extends CBase
{
    protected $method_block = 'account';

    /**
     * @param string $search Player nickname
     */
    public function getPlayer(string $search)
    {
        $method_name = 'list';
        $options = [
            'search' => $search,
            'type' => 'exact',
        ];
        $params = $this->_prepareParams($options);

        $result = $this->_api($method_name, $params);

        return $result;
    }
}
