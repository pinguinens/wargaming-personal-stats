<?php
namespace Entity\WargamingAPI\WoT;

use Core\CApplication;
use Service\Network\CHeader;
use Entity\WargamingAPI\WoT\CBase;

class CAuth extends CBase
{
    protected static $method_block = 'auth';

    private const EXPIRATION_DIFF = 1209600;

    private $_userAccount = [];

    /**
     * @param array $authRespone Response from api
     *
     * @return null
     * @throws \Core\Exceptions\CAPIException
     */
    private static function _saveAccessTokenFile(array $authRespone)
    {
        $json = json_encode($authRespone);
        $filePath = CApplication::getConfiguration('DOCUMENT_ROOT').'/cache/access_token.json';
        $writeResult = file_put_contents($filePath, $json);

        if ($writeResult === false) {
            $error = [
                'message'   => 'Saving access token file was failed.',
                'value'      => $json,
                'code'      => 2000,
            ];
            throw new \Core\Exceptions\CAPIException($error);
        } else {
            return null;
        }
    }

    /**
     * @return array
     * @throws \Core\Exceptions\CAPIException
     */
    private static function _readAccessTokenFile()
    {
        $filePath = CApplication::getConfiguration('DOCUMENT_ROOT').'/cache/access_token.json';
        if (file_exists($filePath)) {
            $json = file_get_contents($filePath);
            $result = json_decode($json, true);
        } else {
            $error = [
                'message'   => 'Access token file does not exist.',
                'value'      => $json,
                'code'      => 2001,
            ];
            throw new \Core\Exceptions\CAPIException($error);
        }

        return $result;
    }

    public function __construct()
    {
        try {
            $this->_userAccount = $this->_readAccessTokenFile();
        } catch (\Core\Exceptions\CAPIException $e) {
            if ($e->getCode() === 2001) {
                $this->_userAccount = [];
            }
        }
    }

    /**
     * @return string
     * @throws \Core\Exceptions\CAPIException
     */
    public function makeAuthLink()
    {
        $method_name = 'login';
        $options = [
            'nofollow' => 1,
            'expires_at' => $this->EXPIRATION_DIFF,
            'redirect_uri' => CApplication::getConfiguration('AUTH_REDIRECT_URI')
        ];
        $handledResponse = static::_makeRequest($method_name, $options);
        $result = $handledResponse['location'];

        return $result;
    }

    /**
     * @param string $authLink LInk to authentication page
     */
    public function followAuthLink(string $authLink = '')
    {
        if (strlen($authLink)>0) {
            CHeader::followToLocation($authLink);
        }
    }

    /**
     * Method authenticates user based on Wargaming.net ID
     */
    public function loginUser()
    {
        $this->followAuthLink($this->makeAuthLink());
    }

    /**
     * @return bool
     */
    public function isLogin()
    {
        if (array_key_exists('access_token', $this->_userAccount)) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * @param array $getParams GET parameters
     *
     * @return array
     * @throws \Core\Exceptions\CAPIException
     */
    public static function saveNewAccessToken(array $getParams = [])
    {
        if (array_key_exists('access_token', $getParams)) {
            $authRespone = [
                'access_token' => $getParams['access_token'],
                'nickname' => $getParams['nickname'],
                'account_id' => $getParams['account_id'],
                'expires_at' => $getParams['expires_at'],
            ];
            static::_saveAccessTokenFile($authRespone);

            $result = $authRespone;
        } else if (array_key_exists('status', $getParams) && $getParams['status'] === 'error') {
                $error = [
                'message' => $getParams['message'],
                'code' => $getParams['code'],
            ];
            throw new \Core\Exceptions\CAPIException($error);
        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * @return array
     * @throws \Core\Exceptions\CAPIException
     */
    public function prolongateAccessToken()
    {
        $currentTime = time();
        $diff = $currentTime - $this->_userAccount['expires_at'];

        if ($diff >= $this::EXPIRATION_DIFF) {
            $error = [
                'message' => 'Access token was experied.',
                'value' => $this->_userAccount,
                'field' => 'diff',
                'code' => 2004,
            ];
            throw new \Core\Exceptions\CAPIException($error);
        }

        if ($diff < $this::EXPIRATION_DIFF) {
            $method_name = 'prolongate';
            $options = [
                'access_token' => $this->_userAccount['access_token'],
                'expires_at' => $this::EXPIRATION_DIFF,
            ];
            $handledResponse = static::_makeRequest($method_name, $options);
            $newAccessToken = array_merge($this->_userAccount, $handledResponse);
            static::_saveAccessTokenFile($newAccessToken);

            $this->_userAccount = $newAccessToken;
            $result = $this->_userAccount;
        }

        return $result;
    }

    /**
     * @return array
     * @throws \Core\Exceptions\CAPIException
     */
    public function logoutUser()
    {
        $method_name = 'logout';
        $options = [
            'access_token' => $this->_userAccount['access_token'],
        ];
        $handledResponse = static::_makeRequest($method_name, $options);
        $newAccessToken = [];
        static::_saveAccessTokenFile($newAccessToken);

        $this->_userAccount = $newAccessToken;
        $result = $this->_userAccount;

        return $result;
    }

    /**
     * @return array|null
     */
    public function getAccessToken()
    {
        if (array_key_exists('access_token', $this->_userAccount)) {
            $result = $this->_userAccount['access_token'];
        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * @return array|null
     */
    public function getAuthInfo()
    {
        if (array_key_exists('access_token', $this->_userAccount)) {
            $result = $this->_userAccount;
        } else {
            $result = null;
        }

        return $result;
    }
}
