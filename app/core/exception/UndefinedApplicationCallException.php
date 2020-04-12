<?php


namespace app\core\exception;

use Exception;
use Throwable;

/***
 * Class UndefinedApplicationCallException
 * @package app\core\exception
 *
 *Throws when somebody trying to call undefined application
 */
class UndefinedApplicationCallException extends Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = "You are not able to call this application in ".$this->getFile()." line ".$this->getLine()." because of : ".$message;
    }

}
