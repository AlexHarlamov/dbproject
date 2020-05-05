<?php


namespace app\core\state;


use app\core\application\database\FR_DatabaseWorker;
use app\core\CoreState;
use app\core\exception\UndefinedApplicationCallException;
use app\core\exception\UndefinedEnvVariableException;
use app\core\exception\UndefinedMethodCallException;
use app\core\exception\UndefinedTemplateException;
use app\core\util\App;
use app\core\util\Env;
use Exception;

include "lemma_language_lib.php";

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
        try {
            switch (Env::get("FR_ACTION")){
                case "GET":
                    if(Env::contains("ELEMENT_ID") ){
                        if(Env::contains("TEMPLATE_ID")){
                            $this->prepareElementData(Env::get("ELEMENT_ID"),Env::get("TEMPLATE_ID"));
                        }
                        else{
                            $this->prepareElementData(Env::get("ELEMENT_ID"),DEFAULT_ELEMENT_TEMPLATE_ID);
                        }
                    }
                    elseif(Env::contains("CLASS_ID") ){

                        if(Env::contains("TEMPLATE_ID")){
                            $this->prepareClassData(Env::get("CLASS_ID"),Env::get("TEMPLATE_ID"));
                        }
                        else{
                            $this->prepareClassData(Env::get("CLASS_ID"),DEFAULT_CLASS_TEMPLATE_ID);
                        }
                    }
                    break;
                default :
                    throw new Exception();
            }
        } catch (Exception $e) {
            //TODO: Do not ignore
        }

        return $this->fr_DefaultNextCoreStateAfterPrepareDate;
    }


    /**
     * @param $classId
     * @param $templateId
     *
     * Prepare data for Class
     */

    private function prepareClassData($classId,$templateId){

        $function = '@class('.$classId.','.$templateId.')';
        $result = classPrepare($function,$classId,$templateId);

        $templateNode['CURRENT_TEMPLATE'] = $function;
        $templateNode[$result['tempK']] = $result['tempV'];
        $dataNode[$result['dataK']] = $result['dataV'];

        Env::set("VIEW_TEMPLATE",$templateNode);
        Env::set("VIEW_DATA",$dataNode);
    }

    /**
     * @param $elementId
     * @param $templateId
     *
     * Prepare data for Element
     *
     */
    private function prepareElementData($elementId,$templateId){

        $function = '@element('.$elementId.','.$templateId.')';
        $result = elementPrepare($function,$elementId,$templateId);

        $templateNode['CURRENT_TEMPLATE'] = $function;
        $templateNode[$result['tempK']] = $result['tempV'];
        $dataNode[$result['dataK']] = $result['dataV'];

        Env::set("VIEW_TEMPLATE",$templateNode);
        Env::set("VIEW_DATA",$dataNode);

    }


    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }
}
