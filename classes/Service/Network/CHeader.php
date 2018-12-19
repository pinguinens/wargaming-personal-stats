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

    /**
     * Reloads current script
     */
    public static function reload()
    {
        header('Location: /');
    }
}
