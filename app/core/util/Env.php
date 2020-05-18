<?php


namespace app\core\util;

use app\core\exception\UndefinedEnvVariableException;

/***
 * Class Env
 * @package app\core\util
 *
 *
 */
class Env
{

    private static array $fr_Env;

    /**
     * @param string $varName
     * @param $varValue
     *
     * Set environment variable value by name
     */

    public static function set(string $varName, $varValue): void{
        self::$fr_Env[$varName] = $varValue;
    }

    /**
     * @param string $varName
     * @return mixed
     * @throws UndefinedEnvVariableException
     *
     * Get environment variable value by name
     */

    public static function get(string $varName)
    {
        if(isset(self::$fr_Env[$varName])){
            return self::$fr_Env[$varName];
        }else{
            throw new UndefinedEnvVariableException("environment variable $varName is not defined");
        }
    }
    /**
     * @param string $varName
     * @return mixed
     * @throws UndefinedEnvVariableException
     *
     * Returns true environment contains variable with name
     */

    public static function contains(string $varName)
    {
        return isset(self::$fr_Env[$varName]);
    }


}
