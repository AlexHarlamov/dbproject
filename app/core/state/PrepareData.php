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
        }

        return $this->fr_DefaultNextCoreStateAfterPrepareDate;
    }

    private function checkRegularExpressions(){
        //check for regular expr inside "VIEW_TEMPLATE"
        //@list_table:<lemma_current>
        $data = [];
        Env::set("VIEW_TEMPLATE__REGEX__LIST_TABLE__NAME_CLASSES",$data);
    }

    /**
     * @param $classId
     * @param $templateId
     *
     * Prepare data for Class
     * Предполагаем, что всегда есть дефолтный шаблон,
     * если его нет - то это серьезная проблема, которую срочно нужно решать
     */

    private function prepareClassData($classId,$templateId){

        try {
            Env::set("VIEW_TEMPLATE",$this->getTemplate($templateId));
        } catch (UndefinedTemplateException $e) {
            Env::set("FR_ACTION", "ERROR");
            Env::set("TEMPLATE_ID", NO_ACTION_EXCEPTION_TEMPLATE_ID);
        }

        try {
            $arr = App::call(DATABASE_WORKER, "select", [
                "from" => "lemma_classes",
                "what" => [
                    "*"
                ],
                "filter" => [
                    "ID"
                ],
                "conditions" => [
                    $classId
                ]
            ]);
            Env::set("VIEW_DATA",$arr[0]); //get data

        } catch (UndefinedApplicationCallException $e) {
        } catch (UndefinedMethodCallException $e) {
        }

    }

    /**
     * @param $elementId
     * @param $templateId
     *
     * Prepare data for Element
     */

    private function prepareElementData($elementId,$templateId){

        $currentTemplate ="";

        try {
            $currentTemplate = $this->getTemplate($templateId);
        } catch (UndefinedTemplateException $e) {
            Env::set("FR_ACTION", "ERROR");
            Env::set("TEMPLATE_ID", NO_ACTION_EXCEPTION_TEMPLATE_ID);
        }


    }

    /**
     * @param $templateId
     * @throws UndefinedTemplateException
     *
     * Returns body of template by it's id
     */
    private function getTemplate($templateId){
        try {
            $arr = App::call(DATABASE_WORKER, "select", [
                "from" => "lemma_templates",
                "what" => [
                    "BODY"
                ],
                "filter" => [
                    "ID"
                ],
                "conditions" => [
                    $templateId
                ]
            ]);
            if (empty($arr)) {
                throw  new UndefinedTemplateException("template with id $templateId does not exist");
            }else{
                return ($arr[0])["BODY"];
            }
        } catch (UndefinedApplicationCallException $e) {
        } catch (UndefinedMethodCallException $e) {
            //TODO:Handle
        }
    }



    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }
}
