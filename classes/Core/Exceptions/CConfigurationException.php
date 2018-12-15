<?php
namespace Core\Exceptions;

/** Config Exception class*/
final class CConfigurationException extends \Exception
{
    protected $prop;
    protected $configFilePath;

    /**
     * @param array $error Error description array
     */
    public function __construct(array $error)
    {
        parent::__construct($error['message'], $error['code']);
        $this->prop = (array_key_exists('prop', $error))
            ? $error['prop']
            : null;
        $this->configFilePath = (array_key_exists('configFilePath', $error))
            ? $error['configFilePath']
            : null;
    }

    /**
     * @return mixed
     */
    final public function getProperty()
    {
        return $this->prop;
    }

    /**
     * @return mixed
     */
    final public function getConfigFilePath()
    {
        return $this->configFilePath;
    }
}
