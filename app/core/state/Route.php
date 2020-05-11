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
                case "POST":
                    $this->checkAvailablePostScripts();
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

        if(Env::contains("QWERTY")){

            $qwerty = Env::get("QWERTY");

            if(isset($qwerty["WRAPPER"]) && ($qwerty["WRAPPER"] == 0 || $qwerty["WRAPPER"] == 1) ){
                Env::set("WRAPPER",$qwerty["WRAPPER"]);
            }else{
                Env::set("WRAPPER",1);
            }

            if(isset($qwerty["ELEMENT_ID"])){
                Env::set("ELEMENT_ID",$qwerty["ELEMENT_ID"]);
                if(isset($qwerty["TEMPLATE_ID"])){
                    Env::set("TEMPLATE_ID",$qwerty["TEMPLATE_ID"]);
                }
            }elseif(isset($qwerty["CLASS_ID"])){
                Env::set("CLASS_ID",$qwerty["CLASS_ID"]);
                if(isset($qwerty["TEMPLATE_ID"])){
                    Env::set("TEMPLATE_ID",$qwerty["TEMPLATE_ID"]);
                }
            }else{
                throw new Exception();
            }

        }

    }

    private function checkAvailablePostScripts(){

        if(Env::contains("QWERTY")){

            $qwerty = Env::get("QWERTY");

            if(isset($qwerty["WRAPPER"]) && ($qwerty["WRAPPER"] == 0 || $qwerty["WRAPPER"] == 1) ){
                Env::set("WRAPPER",$qwerty["WRAPPER"]);
            }else{
                Env::set("WRAPPER",1);
            }

            if(isset($qwerty["ACTION"])){

                switch ($qwerty["ACTION"]){
                    case "0":
                        $d = null;
                        $putdata = fopen("php://input", "r");
                        while ($data = fread($putdata, 1024)){
                            $d =$d.$data;
                        }
                        parse_str($d,$formData);
                        Env::set("FR_FORM_DATA_TO_SAVE",$formData);
                        break;
                    default:
                        throw new Exception();
                }

            }else{
                throw new Exception();
            }

        }
    }

    private function prepareEnvVariables(){

        $url = $_SERVER['REQUEST_URI'];
        $path= parse_url($url, PHP_URL_PATH);
        $array = explode("/", trim($path, "/"));

        $pos = strpos($url , '?');
        if($pos !== false){
            $str = substr($url , $pos+1);
            parse_str($str,$qwert);
            Env::set("QWERTY",$qwert);
        }

        Env::set("FR_URL",$url);
        Env::set("FR_PATH",$path);
        Env::set("FR_ACTION",$array[0]);

    }


}
