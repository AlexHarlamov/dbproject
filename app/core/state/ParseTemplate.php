<?php


namespace app\core\state;


use app\core\CoreState;
use app\core\exception\UndefinedApplicationCallException;
use app\core\exception\UndefinedEnvVariableException;
use app\core\exception\UndefinedMethodCallException;
use app\core\util\App;
use app\core\util\Env;
use Exception;

class ParseTemplate implements CoreState
{
    /**
     * @var CoreState $fr_DefaultNextCoreStateAfterParseTemplate
     *
     * required return CoreState type variable of lifecycle core state.
     *
     */

    private CoreState $fr_DefaultNextCoreStateAfterParseTemplate;

    public function __construct()
    {
        $this->fr_DefaultNextCoreStateAfterParseTemplate = new Terminate();
    }


    function fr_defaultAction(): CoreState
    {
        // TODO: Implement fr_defaultAction() method.

        $template = Env::get("VIEW_TEMPLATE");
        $data = Env::get("VIEW_DATA");
        $html = recursionParseLoop($template,$data);

        Env::set("FR_OUTPUT_BUFFER",$html);


        return $this->fr_DefaultNextCoreStateAfterParseTemplate;
    }

    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }
}
