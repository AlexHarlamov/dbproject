<?php


namespace app\core\state;


use app\core\CoreState;

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
        // TODO: Implement fr_defaultAction() method.

        return $this->fr_DefaultNextCoreStateAfterRoute;
    }

    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }
}
