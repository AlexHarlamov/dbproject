<?php


namespace app\core\state;


use app\core\CoreState;
use app\core\exception\IllegalAppRegistrationException;
use app\core\exception\IllegalHookException;
use app\core\util\App;

/**
 * Class Init
 * @package core.state;
 *
 * Uploads default settings and prepares framework for work,
 * has included callback which constructs Application
 *
 */

class Init implements CoreState
{

    /**
     * @var CoreState $fr_DefaultNextCoreStateAfterInit
     *
     * required return CoreState type variable of lifecycle core state.
     *
    */

    private CoreState $fr_DefaultNextCoreStateAfterInit;

    /**
     * imports settings,
     * instantiates next core lifecycle state and returns it
     *
     * @return CoreState|Route
     */

    function fr_defaultAction() : CoreState
    {
        require_once ROOT_DIR."app/defines/default.php";
        require_once ROOT_DIR."settings/settings.php";

        $this->fr_DefaultNextCoreStateAfterInit = new Route();

        return $this->fr_DefaultNextCoreStateAfterInit;
    }

    /**
     * By default has no hooks
     * @param null $state_option
     * @throws IllegalHookException
     */

    function fr_executeHookCallbacks( $state_option = null)
    {
        throw new IllegalHookException("Init lifecycle state cant have hooks");
    }

    /**
     * Save list of the instantiated applications
     * @param array $fr_applicationsList
     */

    function fr_exportApplications (array $fr_applicationsList){
        try {
            App::setFrApp($fr_applicationsList);
        } catch (IllegalAppRegistrationException $e) {
            echo DEFAULT_APP_REG_ERR;
        }
    }
}
