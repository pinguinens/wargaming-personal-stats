<?php
namespace Entity\WargamingAPI\WoT;

use Core\CApplication;
use Service\Network\CHeader;
use Entity\WargamingAPI\WoT\CBase;

class CAuth extends CBase
{
    protected $method_block = 'auth';

    private const EXPIRATION_DIFF = 1209600;

    private $_userAccount = [];

    /**
     * @param array $authRespone Response from api
     *
     * @return null
     * @throw \Core\Exceptions\CAPIException
     */
    private function _saveAccessTokenFile(array $authRespone)
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
     * @throw \Core\Exceptions\CAPIException
     */
    private function _readAccessTokenFile()
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

    /**
     * @return string
     * @throw \Core\Exceptions\CAPIException
     */
    public function makeAuthLink()
    {
        $method_name = 'login';
        $options = [
            'nofollow' => 1,
            'expires_at' => $this->EXPIRATION_DIFF,
            'redirect_uri' => CApplication::getConfiguration('AUTH_REDIRECT_URI')
        ];
        $params = $this->_prepareParams($options);

        $response = $this->_api($method_name, $params);
        $openIDlink = json_decode($response, true);
        if ($openIDlink['status'] === 'ok') {
            $result = $openIDlink['data']['location'];
        } else {
            $error = [
                'message' => $openIDlink['error']['message'],
                'field' => $openIDlink['error']['field'],
                'value' => $openIDlink['error']['value'],
                'field' => $openIDlink['error']['code'],
            ];
            throw new \Core\Exceptions\CAPIException($error);
        }

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
    public function authUser()
    {
        $this->followAuthLink($this->makeAuthLink());
    }

    /**
     * @param array $getParams GET parameters
     *
     * @return array
     * @throw \Core\Exceptions\CAPIException
     */
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
            try {
                $this->_userAccount = $this->_readAccessTokenFile();
            } catch (\Core\Exceptions\CAPIException $e) {
                if ($e->getCode() === 2001) {
                    $this->_userAccount = null;
                } else {
                    $error = [
                        'message'   => 'Authentication was failed.',
                        'code'      => 2002,
                    ];
                    throw new \Core\Exceptions\CAPIException($error);
                }
            }
        }

        if (count($this->_userAccount) === 0) {
            $result = false;
        } else {
            $result = $this->_userAccount;
        }

        return $result;
    }

    /**
     * @return array
     * @throw \Core\Exceptions\CAPIException
     */
    public function prolongateAccessToken()
    {
        if (count($this->_userAccount) === 0) {
            $this->_userAccount = $this->_readAccessTokenFile();
        }

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
            $params = $this->_prepareParams($options);
            $response = $this->_api($method_name, $params);

            $authRespone = json_decode($response, true);
            if ($authRespone['status'] === 'ok') {
                $openID = $authRespone['data'];
                $newAccessToken = array_merge($this->_userAccount, $openID);
                $this->_saveAccessTokenFile($newAccessToken);
    
                $this->_userAccount = $newAccessToken;
                $result = $this->_userAccount;
            } else {
                $error = [
                    'message' => $authRespone['error']['message'],
                    'field' => $authRespone['error']['field'],
                    'value' => $authRespone['error']['value'],
                    'field' => $authRespone['error']['code'],
                ];
                throw new \Core\Exceptions\CAPIException($error);
            }
        }

        return $result;
    }

    /**
     * @return array
     * @throw \Core\Exceptions\CAPIException
     */
    public function logoutUser()
    {
        if (count($this->_userAccount) === 0) {
            $this->_userAccount = $this->_readAccessTokenFile();
        }

        $method_name = 'logout';
        $options = [
            'access_token' => $this->_userAccount['access_token'],
        ];
        $params = $this->_prepareParams($options);
        $response = $this->_api($method_name, $params);

        $APIrespone = json_decode($response, true);
        if ($APIrespone['status'] === 'ok') {
            $newAccessToken = [];
            $this->_saveAccessTokenFile($newAccessToken);

            $this->_userAccount = $newAccessToken;
            $result = $this->_userAccount;
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
