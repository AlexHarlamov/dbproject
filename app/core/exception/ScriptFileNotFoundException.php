<?php


namespace app\core\exception;


use Exception;
use Throwable;

/**
 * Class ScriptFileNotFoundException
 * @package app\core\exception
 *
 * Throws when somebody trying register file which not exist
 */
class ScriptFileNotFoundException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = "$message JavaScript file does not exist in ".DEFAULT_JS_PATH;
    }
}
