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
        parent::__construct($error['message'], $error['code']);
        $this->field = $error['field'];
        $this->value = $error['value'];
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
