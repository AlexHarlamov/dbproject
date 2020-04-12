<?php


namespace app\core\exception;

use Exception;
use Throwable;

/**
 * Class UndefinedMethodCallException
 * @package app\core\exception
 *
 * Throws when somebody trying call undefined application's method
 */
class UndefinedMethodCallException extends Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = "You are not able to call this method in ".$this->getFile()." line ".$this->getLine()." because of : ".$message;
    }

}
