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
        $error['message'] = (array_key_exists('message', $error))
            ? $error['message']
            : null;
        $error['code'] = (array_key_exists('code', $error))
            ? $error['code']
            : null;
        $this->prop = (array_key_exists('prop', $error))
            ? $error['prop']
            : null;
        $this->configFilePath = (array_key_exists('configFilePath', $error))
            ? $error['configFilePath']
            : null;
        parent::__construct($error['message'], $error['code']);
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
