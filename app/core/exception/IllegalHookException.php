<?php


namespace app\core\exception;

use Exception;
use Throwable;

/**
 * Class IllegalHookException
 *
 * Should be thrown if lifecycle hook cant be used
*/

class IllegalHookException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = "You are not able to use this hook in ".$this->getFile()." line ".$this->getLine()." because of : ".$message;
    }

}
