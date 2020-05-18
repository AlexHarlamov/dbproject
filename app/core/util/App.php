<?php

namespace app\core\util;

use app\core\exception\IllegalAppRegistrationException;
use app\core\exception\UndefinedApplicationCallException;
use app\core\exception\UndefinedMethodCallException;
use Exception;

/***
 * Class App
 * @package app\core\util
 */
class App
{

    private static array $fr_App;

    /**
     * @param array $fr_App
     * @throws IllegalAppRegistrationException
     *
     * Method for importing applications' instances
     */
    public static function setFrApp(array $fr_App): void
    {
        if(!empty(self::$fr_App)){
            throw new IllegalAppRegistrationException("application list is already defined");
        }else{
            self::$fr_App = $fr_App;
        }
    }

    /**
     * @param $appName
     * @param $methodName
     * @param $variables
     * @return mixed
     * @throws UndefinedApplicationCallException
     * @throws UndefinedMethodCallException
     * @throws Exception
     *
     * Provides functionality of calling methods in the safe environment
     */
    public static function call($appName, $methodName, $variables = []){
        if(isset(self::$fr_App[$appName])){
            if(method_exists(self::$fr_App[$appName],$methodName)){
                try{
                    return (self::$fr_App[$appName])->{$methodName}($variables);
                }catch (Exception $e){
                    throw $e;
                }
            }else{
                throw new UndefinedMethodCallException("method $methodName is not available for $appName application");
            }
        }else{
            throw new UndefinedApplicationCallException(" $appName is not registered");
        }
    }

    /**
     * @param $appName
     * @return mixed
     * @throws UndefinedApplicationCallException
     *
     * Provides functionality of getting instance of the application
     */

    public static function getApp($appName){
        if(isset(self::$fr_App[$appName])){
            return self::$fr_App[$appName];
        }else{
            throw new UndefinedApplicationCallException(" $appName is not registered");
        }
    }
}
