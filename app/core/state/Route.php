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
            switch (Env::get("FR_ACTION")[0]){
                case "":
                    $this->checkAvailableHelloScripts();
                    break;
                case "nullInterface":
                    switch (Env::get("FR_ACTION")[1]){
                        case "":
                            $this->checkAvailableNullInterfaceScripts();
                            break;
                        case "GET":
                            $this->checkAvailableGetScripts();
                            break;
                        case "POST":
                            $this->checkAvailablePostScripts();
                            break;
                    }
                    break;
                case "userInterface":
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

    private function checkAvailableHelloScripts(){
        $this->setWrapper();
        Env::set("SSE",SITE_STRUCTURE_ELEMENT);
        Env::set("SST",SITE_HELLO_STRUCTURE_TEMPLATE);
    }

    private function checkAvailableNullInterfaceScripts(){
        $this->setWrapper();
        Env::set("SSE",SITE_STRUCTURE_ELEMENT);
        Env::set("SST",SITE_NULL_STRUCTURE_TEMPLATE);
    }

    /**
     * @throws UndefinedEnvVariableException
     * Роутинг любого GET
     */
    private function checkAvailableGetScripts(){

        Env::set("SSE",SITE_STRUCTURE_ELEMENT);
        Env::set("SST",SITE_NULL_STRUCTURE_TEMPLATE);
        $this->setWrapper();

        if(Env::contains("QWERTY") && isset(Env::get("QWERTY")["OBJ"])){

            $qwerty = Env::get("QWERTY");

            switch ($qwerty["OBJ"]){
                case GET_CLASSES:
                        break;
                case GET_CLASS:
                case GET_ELEMENTS:
                    if(isset($qwerty["CLASS_ID"])){
                        Env::set("CLASS_ID",$qwerty["CLASS_ID"]);
                    }else{
                        throw new Exception();
                    }
                    break;
                case GET_ELEMENT:
                    if(isset($qwerty["ELEMENT_ID"])){
                        Env::set("ELEMENT_ID",$qwerty["ELEMENT_ID"]);
                        if(isset($qwerty["TEMPLATE_ID"])){
                            Env::set("TEMPLATE_ID",$qwerty["TEMPLATE_ID"]);
                        }
                    }else{
                        throw new Exception();
                    }
                    break;
                case GET_CHANGE_CLASS:
                    if(isset($qwerty["CLASS_ID"])){//это еще не работает
                        Env::set("CLASS_ID",$qwerty["CLASS_ID"]);
                    }else{
                        //создаем новый класс
                    }
                    break;
                case GET_CHANGE_ELEMENT:
                    break;
                case GET_CLASS_TEMPLATES_ID:
                    if(isset($qwerty["CLASS_ID"])){
                        Env::set("CLASS_ID",$qwerty["CLASS_ID"]);
                    }
                    break;
                case GET_CLASS_RELATION_TO:
                case GET_CLASS_RELATION_FROM:
                if(isset($qwerty["CLASS_ID"])){
                    Env::set("CLASS_ID",$qwerty["CLASS_ID"]);
                }
                break;
                default:
                    throw new Exception();
            }

            Env::set("OBJ",$qwerty["OBJ"]);

        }else{
            throw new Exception();
        }

    }

    /**
     * @throws UndefinedEnvVariableException
     *
     * Роутинг любого POST
     */
    private function checkAvailablePostScripts(){

        Env::set("SSE",SITE_STRUCTURE_ELEMENT);
        Env::set("SST",SITE_NULL_STRUCTURE_TEMPLATE);
        $this->setWrapper();

        if(Env::contains("QWERTY") && isset(Env::get("QWERTY")["OBJ"])){

            $qwerty = Env::get("QWERTY");

            switch ($qwerty["OBJ"]){
                case POST_CHANGE_CLASS:
                    $d = null;
                    $putdata = fopen("php://input", "r");
                    while ($data = fread($putdata, 1024)){
                        $d =$d.$data;
                    }
                    parse_str($d,$formData);
                    Env::set("FR_FORM_DATA_TO_SAVE",$formData);
                    break;
                case POST_CHANGE_ELEMENT:
                    break;
                case POST_CHANGE_RELATION:
                    break;
                case POST_CHANGE_LINK:
                    break;
                default:
                    throw new Exception();

            }

            Env::set("OBJ",$qwerty["OBJ"]);

        }else{
            throw new Exception();
        }
    }

    /**
     * разбор url
     */
    private function prepareEnvVariables(){

        $url = $_SERVER['REQUEST_URI'];
        $path= parse_url($url, PHP_URL_PATH);
        $array = explode("/", trim($path, "/"));

        if(!isset($array[1])){
            $array[1]="";
        }

        $pos = strpos($url , '?');
        if($pos !== false){
            $str = substr($url , $pos+1);
            parse_str($str,$qwert);
            Env::set("QWERTY",$qwert);
        }

        Env::set("FR_URL",$url);
        Env::set("FR_PATH",$path);
        Env::set("FR_ACTION",$array);

    }

    /**
     * @throws UndefinedEnvVariableException
     *
     * если в url есть WRAPPER=0, то оболочка,т.е. вид сайта не добавляется к контенту
     * если в url нет, то устанавливается = 1
     */
    private function setWrapper(){
        if(!Env::contains("QWERTY")){
            Env::set("WRAPPER",1);
        }
        else{
            $qwerty = Env::get("QWERTY");

            if(isset($qwerty["WRAPPER"]) && ($qwerty["WRAPPER"] == 0 || $qwerty["WRAPPER"] == 1) ){
                Env::set("WRAPPER",$qwerty["WRAPPER"]);
            }else{
                Env::set("WRAPPER",1);
            }
        }
    }


}
