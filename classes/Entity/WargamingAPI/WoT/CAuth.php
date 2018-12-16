<?php
namespace Entity\WargamingAPI\WoT;

use Entity\WargamingAPI\WoT\CBase;

class CAuth extends CBase
{
    protected $method_block = 'auth';
    private $_userAccount = [];

    /**
     * @param
     */
    private function _saveAccessTokenFile(array $authRespone)
    {
        global $APPLICATION;
        $json = json_encode($authRespone);
        $filePath = $APPLICATION->getConfiguration('DOCUMENT_ROOT').'/cache/access_token.json';
        $writeResult = file_put_contents($filePath, $json);

        if($writeResult === false) {
            $error = [
                'message'   => 'Configuration property does not exist.',
                'prop'      => $prop,
                'code'      => 1001,
            ];
            throw new \Core\Exceptions\CAPIException($error);
        } else {
            return null;
        }
    }

    private function _readAccessTokenFile() {
        global $APPLICATION;
        $filePath = $APPLICATION->getConfiguration('DOCUMENT_ROOT').'/cache/access_token.json';
        if (file_exists($filePath)) {
            $json = file_get_contents($filePath);
            $result = json_decode($json, true);
        }

        return $result;
    }

    /**
     * @param string $search Player nickname
     */
    public function makeAuthLink()
    {
        $method_name = 'login';
        $options = [
            'nofollow' => 1,
            'expires_at' => 1209599,
            'redirect_uri' => 'https://0.0.0.0/'
        ];
        $params = $this->_prepareParams($options);

        $response = $this->_api($method_name, $params);
        $openIDlink = json_decode($response, true);
        if ($openIDlink['status'] === 'ok') {
            $result = $openIDlink['data']['location'];
        }

        return $result;
    }

    public function checkAuth(array $getParams = [])
    {
        if (array_key_exists('access_token', $getParams)) {
            $authRespone = [
                'access_token' => $getParams['access_token'],
                'nickname' => $getParams['nickname'],
                'account_id' => $getParams['account_id'],
                'expires_at' => $getParams['expires_at'],
            ];
            $this->_saveAccessTokenFile($authRespone);

            $this->_userAccount = $authRespone;
        } else {
            $this->_userAccount = $this->_readAccessTokenFile();
        }
        $result = $this->_userAccount;

        return $result;
    }
}
