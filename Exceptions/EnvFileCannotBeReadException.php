<?php

namespace Uwi\Exceptions;

class EnvFileCannotBeReadException extends \Exception
{
    protected $message = 'Env file cannot be read';
    protected $code = E_USER_ERROR;

    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct($this->message . ' [' . $message . ']', $this->code, $previous);
    }
}
