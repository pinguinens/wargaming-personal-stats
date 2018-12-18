<?php
namespace Core\Exceptions;

/** API Exception class*/
final class CAPIException extends \Exception
{
    protected $field;
    protected $value;

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
        $this->field = $error['field'];
        $this->value = $error['value'];
        parent::__construct($error['message'], $error['code']);
    }

    /**
     * @return mixed
     */
    final public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    final public function getField()
    {
        return $this->field;
    }
}
