<?php
namespace Service\Network;

/**
 * cURL wrapper class
 */
class CcURL
{
    /**
     * @param string $url
     * @param array $params
     * 
     * @return mixed
     */
    public static function post(string $url, array $params)
    {
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        curl_close($curl);

        if ($result === false) {
            return null;
        } else {
            return $result;
        }
    }

    /**
     * @param string $url
     * @param array $params
     * 
     * @return mixed
     */
    public static function get(string $url, array $params = [])
    {
        if (count($params) !== 0) {
            $ecodedURL = $url .'?'. http_build_query($params);
        } else {
            $ecodedURL = $url;
        }
        $options = [
            CURLOPT_URL => $ecodedURL,
            CURLOPT_RETURNTRANSFER => true,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        curl_close($curl);

        if ($result === false) {
            return null;
        } else {
            return $result;
        }
    }
}
