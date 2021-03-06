<?php


namespace app\core\state;


use app\core\CoreState;
use app\core\exception\UndefinedEnvVariableException;
use app\core\util\App;
use app\core\util\Env;

class Terminate implements CoreState
{

    /**
     * @var CoreState $fr_DefaultNextCoreStateAfterExit
     *
     * required return CoreState type variable of lifecycle core state.
     *
     */

    private CoreState $fr_DefaultNextCoreStateAfterExit;

    public function __construct()
    {
        $this->fr_DefaultNextCoreStateAfterExit = new ExitPoint();
    }


    private function defaultOutputAction(){

        try {
            App::call(JAVASCRIPT_WORKER,"processAttaching");
            App::call(HTML_WORKER,"constructDocument");
            echo Env::get("HTML_DOCUMENT");
        } catch (UndefinedEnvVariableException $e) {
        }

    }

    function fr_defaultAction(): CoreState
    {
        // TODO: Implement fr_defaultAction() method.

        $this->defaultOutputAction();

        return $this->fr_DefaultNextCoreStateAfterExit;
    }

    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }
}
