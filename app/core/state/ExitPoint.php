<?php


namespace app\core\state;


use app\core\CoreState;

class ExitPoint implements CoreState
{

    function fr_defaultAction(): CoreState
    {
        // TODO: Implement fr_defaultAction() method.
        return $this;
    }

    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }
}
