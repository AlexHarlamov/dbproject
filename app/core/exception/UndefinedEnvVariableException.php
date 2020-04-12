<?php


namespace app\core\exception;


use Exception;
use Throwable;

/***
 * Class UndefinedEnvVariableException
 * @package app\core\exception
 *
 * Throws when somebody trying to get undefined Env variable
 */
class UndefinedEnvVariableException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = "You are not able to use this variable in ".$this->getFile()." line ".$this->getLine()." because of : ".$message;
    }
}
