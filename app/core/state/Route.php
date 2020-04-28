<?php


namespace app\core\state;


use app\core\CoreState;
use app\core\exception\UndefinedEnvVariableException;
use app\core\util\Env;
use Exception;


/**
 * Class Route
 * @package app\core\state
 *
 * Routing HTTP - request
 *
 *
 */

/**
 * Class Route
 * @package app\core\state
 */
class Route implements CoreState
{

    /**
     * @var CoreState $fr_DefaultNextCoreStateAfterRoute
     *
     * required return CoreState type variable of lifecycle core state.
     *
     */


    private CoreState $fr_DefaultNextCoreStateAfterRoute;

    public function __construct()
    {
        $this->fr_DefaultNextCoreStateAfterRoute = new PrepareData();
    }


    function fr_defaultAction(): CoreState
    {

        $this->prepareEnvVariables();

        try {
            switch (Env::get("FR_ACTION")){
                case "GET":
                    $this->checkAvailableGetScripts();
                    break;
                default :
               throw new Exception();
            }
        }catch (UndefinedEnvVariableException $e){
            Env::set("FR_ACTION", "ERROR");
            Env::set("TEMPLATE_ID", NO_ACTION_EXCEPTION_TEMPLATE_ID);
        }catch (Exception $e){

        }

        return $this->fr_DefaultNextCoreStateAfterRoute;
    }

    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }


    private function checkAvailableGetScripts(){

        if(isset($_GET["ELEMENT_ID"])){
            Env::set("ELEMENT_ID",$_GET["ELEMENT_ID"]);
            if(isset($_GET["TEMPLATE_ID"])){
                Env::set("TEMPLATE_ID",$_GET["TEMPLATE_ID"]);
            }
        }elseif(isset($_GET["CLASS_ID"])){
            Env::set("CLASS_ID",$_GET["CLASS_ID"]);
            if(isset($_GET["TEMPLATE_ID"])){
                Env::set("TEMPLATE_ID",$_GET["TEMPLATE_ID"]);
            }
            throw new Exception();
        }else{
            throw new Exception();
        }
    }

    private function prepareEnvVariables(){

        $url = $_SERVER['REQUEST_URI'];
        $path= parse_url($url, PHP_URL_PATH);
        $array = explode("/", trim($path, "/"));

        Env::set("FR_URL",$url);
        Env::set("FR_PATH",$path);
        Env::set("FR_ACTION",$array[0]);

    }

    /**
     * functions handle html methods
     */
    private function getHandler()
    {
        $queries = array();
        parse_str($_SERVER['QUERY_STRING'], $queries);
        foreach ($queries as $k => $v){
            Env::set($k,$v);
        }

        $this->fr_DefaultNextCoreStateAfterRoute = new PrepareData();

    }

    private function postHandler()
    {

    }

    private function putHandler()
    {

    }

    private function deleteHandler()
    {

    }

}
