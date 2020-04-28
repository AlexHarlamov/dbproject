<?php


namespace app\core\state;


use app\core\CoreState;
use app\core\util\App;
use app\core\util\Env;
use app\core\pages\listclasses;
use Exception;

class PrepareData implements CoreState
{

    /**
     * @var CoreState $fr_DefaultNextCoreStateAfterPrepareDate
     *
     * required return CoreState type variable of lifecycle core state.
     *
     */

    private CoreState $fr_DefaultNextCoreStateAfterPrepareDate;

    public function __construct()
    {
        $this->fr_DefaultNextCoreStateAfterPrepareDate = new ParseTemplate();
    }


    function fr_defaultAction(): CoreState
    {
        // TODO: Implement fr_defaultAction() method.

        try {

            $p = Env::get("page");
            //echo $p;
            $s = "app\core\pages\\$p";
            $s::fr_prepare_data();

        }catch (Exception $e) {
            echo $e;
        }

        return $this->fr_DefaultNextCoreStateAfterPrepareDate;
    }

    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }
}
