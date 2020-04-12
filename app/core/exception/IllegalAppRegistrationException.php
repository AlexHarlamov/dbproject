<?php


namespace app\core\exception;

use Exception;
use Throwable;

/***
 * Class IllegalAppRegistrationException
 * @package app\core\exception
 *
 * Throws when somebody trying to register apps out of Init core state
 */
class IllegalAppRegistrationException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = "You are not able to applications in ".$this->getFile()." line ".$this->getLine()." because of : ".$message;
    }
}
