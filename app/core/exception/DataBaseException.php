<?php


namespace app\core\exception;


use Exception;
use Throwable;

/**
 * Class DataBaseException
 * @package app\core\exception
 *
 */

class DataBaseException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = "Data was not found in ".$this->getFile()." line ".$this->getLine()." because of : ".$message;
    }

}