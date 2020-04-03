<?php


namespace app\core\state;


use app\core\CoreState;

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


    function fr_defaultAction(): CoreState
    {
        // TODO: Implement fr_defaultAction() method.

        return $this->fr_DefaultNextCoreStateAfterExit;
    }

    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }
}
