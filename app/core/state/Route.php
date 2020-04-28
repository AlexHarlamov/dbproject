<?php


namespace app\core\state;


use app\core\CoreState;
use app\core\util\Env;


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
        /* it will be deleted
        $lines = array();
        $fileHandler = fopen("php://input", "r");
        while( !feof($fileHandler)  ) {
            $lines[] = fgets($fileHandler,255);
        }
        fclose($fileHandler);
        print_r($lines);
        //
        $entityBody = file_get_contents('php://input');
        echo $entityBody."<br />";
        */

        $url = $_SERVER['REQUEST_URI'];
        $path= parse_url($url, PHP_URL_PATH);
        $array = explode("/", trim($path, "/"));
        $page = array_pop($array);
        //print_r($page);
        Env::set("page",$page);
        if($page==""){echo "такой страницы нет";}//TODO: обработать
        //echo $page;

        switch ($_SERVER['REQUEST_METHOD']){
            case "GET":{
                $this->getHandler();
                break;}
            case "POST": {
                $this->postHandler();
                break;}
            case "PUT" :{
                $this->putHandler();
                break;}
            case "DELETE":{
                $this->deleteHandler();
                break;}
            default : {
        //TODO: error handling
    }
        }

        return $this->fr_DefaultNextCoreStateAfterRoute;
    }

    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
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
            //echo $k."=".$v;
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
