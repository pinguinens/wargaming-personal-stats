<?php
namespace Service\Network;

/**
 * header wrapper class
 */
class CHeader
{
    /**
     * @param string $url
     */
    public static function followToLocation(string $url)
    {
        header('Location: '. $url);
    }
}
