<?php

namespace app\Exceptions;

use Exception;
use Throwable;

class LogicException extends Exception
{

    public function __construct($message, $code = 0, Throwable $previous = null)
    {   
        $this->exception = $message;
        parent::__construct($message, $code = 0, $previous = null);
    }

    public function __destruct()
    {
        unset($this->exception);
    }

}
