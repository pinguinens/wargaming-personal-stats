<?php
namespace Core;
/**
 * Main application Singleton class
 */

final class CApplication
{
    private static $instance;

    /**
     * @return CApplication
     */
    public static function getInstance(): CApplication
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
