<?php


namespace app\core\state;


use app\core\application\database\FR_DatabaseWorker;
use app\core\CoreState;
use app\core\exception\UndefinedApplicationCallException;
use app\core\exception\UndefinedMethodCallException;
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
            Env::set("VIEW_TEMPLATE", App::call(DATABASE_WORKER, "select", [])); // get template
            $this->checkRegularExpressions();
            Env::set("VIEW_DATA",App::call(DATABASE_WORKER,"select",[])); //get data
        } catch (UndefinedApplicationCallException $e) {
        } catch (UndefinedMethodCallException $e) {
        } catch (Exception $e) {
        }

        return $this->fr_DefaultNextCoreStateAfterPrepareDate;
    }

    private function checkRegularExpressions(){
        //check for regular expr inside "VIEW_TEMPLATE"
        //@list_table:<lemma_classes>
        $data = [];
        Env::set("VIEW_TEMPLATE__REGEX__LIST_TABLE__NAME_CLASSES",$data);
    }

    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }
}
