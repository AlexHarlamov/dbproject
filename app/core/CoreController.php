<?php


namespace app\core;

use app\core\state\Init;
use Exception;

/**
 * @Class CoreController
 *
 * Default entry point of the system, provides control of the execution of the states,
 * handles the error of the execution
 *
 *
*/
class CoreController
{

    /**
     * @var CoreState $fr_DefaultEntryPoint
     *
     * Contains default entry state of the core
     *
    */
    private CoreState $fr_DefaultEntryPoint;

    /**
     * @var CoreState $fr_CurrentState
     *
     * Contains current state of the core execution
     */

    private CoreState $fr_CurrentState;

    /**
     * @var CoreState $fr_NextState
     *
     * Contains next state of the core execution
     */

    private CoreState $fr_NextState;

    public function __construct()
    {
        $this->fr_DefaultEntryPoint = new Init();
        $this->fr_CurrentState = $this->fr_DefaultEntryPoint;
    }

    /**
     * Initiates processing of states execution
     *
     * This function executes default actions of the state, calls it hooks and switches state
     *
     */

    public function execute(){

        $this->fr_NextState = $this->fr_CurrentState->fr_defaultAction();
        $this->fr_CurrentState = $this->fr_NextState;

        while ( get_class($this->fr_NextState) != DEFAULT_EXIT_POINT ){

            try {

                $this->fr_CurrentState->fr_executeHookCallbacks(HOOK_BEFORE);
                $this->fr_NextState = $this->fr_CurrentState->fr_defaultAction();
                $this->fr_CurrentState->fr_executeHookCallbacks(HOOK_AFTER);

                $this->fr_CurrentState = $this->fr_NextState;
            }catch (Exception $exception){
                //TODO : call warning informer and let him decide what to do
            }
        }
    }
}
