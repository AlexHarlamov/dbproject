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
use PDOException;

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
        //подготовка структуры сайта
        $this->prepareSiteStructure(SITE_STRUCTURE_ELEMENT,SITE_STRUCTURE_TEMPLATE);

        try {
            switch (Env::get("FR_ACTION")){
                case "GET":
                    if(Env::contains("ELEMENT_ID") ){
                        if(Env::contains("TEMPLATE_ID")){
                            $this->prepareElementData(Env::get("ELEMENT_ID"),Env::get("TEMPLATE_ID"));
                        }
                        else{
                            //TODO:сделать дефолтный шаблон?
                            $this->prepareElementData(Env::get("ELEMENT_ID"),DEFAULT_ELEMENT_TEMPLATE_ID);
                        }
                    }
                    elseif(Env::contains("CLASS_ID") ){

                        if(Env::contains("TEMPLATE_ID")){
                            //TODO:что ты такое?
                            $this->prepareClassData(Env::get("CLASS_ID"),Env::get("TEMPLATE_ID"));
                        }
                        else{
                            //TODO:сделать дефолтный шаблон
                            $this->prepareClassData(Env::get("CLASS_ID"),DEFAULT_CLASS_TEMPLATE_ID);
                        }
                    }
                    break;
                case "POST":
                    if(Env::contains("FR_FORM_DATA_TO_SAVE")){
                        $message = $this->saveNewClass();
                        $this->preparePostAnswer($message);
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

    /**
     * @param $elementId
     * @param $templateId
     *
     *
     */
    private function prepareSiteStructure($elementId,$templateId){

        $function = '@element('.$elementId.','.$templateId.')';
        $result = elementPrepare($function,$elementId,$templateId);

        $templateNode['CURRENT_TEMPLATE'] = $function;
        $templateNode[$result['tempK']] = $result['tempV'];
        $dataNode[$result['dataK']] = $result['dataV'];

        Env::set("SITE_TEMPLATE",$templateNode);
        Env::set("SITE_DATA",$dataNode);//null

    }

    private function saveNewClass(){
        $message = null;

        $data = Env::get("FR_FORM_DATA_TO_SAVE");

        $classID = null;

        if(isset($data["ClassName"])){

            $fields = [
                "NAME",
                "I_NAME",
                "COMMENTS"
            ];
            $conditions = [
                "NAME"=>$data["ClassName"],
                "I_NAME" =>"0_".$data["ClassName"],
                "COMMENTS" => $data["ClassComment"]
            ];

            try{
                $arr =  App::call(DATABASE_WORKER, "insert", [
                    "into" => "lemma_classes",
                    "fields" => $fields,
                    "conditions" => $conditions
                ]);
            }catch (PDOException $e) {
                $message = $e->getMessage();
            } catch (UndefinedMethodCallException $e) {
            } catch (Exception $e) {
                //TODO:error
            }

            if(empty($arr)){
                //TODO:ERROR
            }
            else{
                $classID = $arr["id"];
            }




            $i = 0;
            while(isset($data["AttributeName".$i])){

                if( isset($data["AttributeComment".$i]) &&
                    isset($data["AttributeType".$i]) &&
                    isset($data["AttributeSize".$i]) &&
                    isset($data["AttributeIndexed".$i]) &&
                    isset($data["AttributeNull".$i])
                ){
                    $fields = [
                        "NAME",
                        "I_NAME",
                        "COMMENTS",
                        "ATTRIBUTE_TYPE",
                        "ATTRIBUTE_SIZE",
                        "OWNER_CLASS_ID",
                        "IS_INDEXED",
                        "IS_NOTNULL"
                              ];
                    $conditions = [
                        "NAME"=>$data["AttributeName".$i],
                        "I_NAME"=>"0_".$data["AttributeName".$i],
                        "COMMENTS"=>$data["AttributeComment".$i],
                        "ATTRIBUTE_TYPE"=>$data["AttributeType".$i],
                        "ATTRIBUTE_SIZE"=>$data["AttributeSize".$i],
                        "OWNER_CLASS_ID"=>$classID,
                        "IS_INDEXED"=>$data["AttributeIndexed".$i],
                        "IS_NOTNULL"=>$data["AttributeNull".$i]
                    ];

                    try{
                        $arr =  App::call(DATABASE_WORKER, "insert", [
                            "into" => "lemma_attributes",
                            "fields" => $fields,
                            "conditions" => $conditions
                        ]);
                    }catch (PDOException $e) {
                        $message = $e->getMessage();
                    } catch (UndefinedMethodCallException $e) {
                    } catch (Exception $e) {
                        //TODO:error
                    }

                    if(empty($arr)){
                        //TODO:ERROR
                    }

                }else{
                    //error
                }

            $i++;
            }

            $i = 0;

            while(isset($data["TemplateName".$i])){

            if( isset($data["TemplateComment".$i]) &&
                isset($data["TemplateBody".$i])
            ){
                $fields = [
                    "NAME",
                    "I_NAME",
                    "COMMENTS",
                    "BODY",
                    "OWNER_CLASS_ID"
                ];
                $conditions = [
                    "NAME"=>$data["TemplateName".$i],
                    "I_NAME"=>"0_".$data["TemplateName".$i],
                    "COMMENTS"=>$data["TemplateComment".$i],
                    "BODY"=>$data["TemplateBody".$i],
                    "OWNER_CLASS_ID"=>$classID
                ];

                try{
                    $arr =  App::call(DATABASE_WORKER, "insert", [
                        "into" => "lemma_templates",
                        "fields" => $fields,
                        "conditions" => $conditions
                    ]);
                }catch (PDOException $e) {
                    $message = $e->getMessage();
                } catch (UndefinedMethodCallException $e) {
                } catch (Exception $e) {
                    //TODO:error
                }

                if(empty($arr)){
                    //TODO:ERROR
                }

            }else{
                //error
            }

            $i++;
        }

        $message = "SUCCESS";

    }else{
            $message = "ClassName not set";
        }

        return $message;
    }

    function fr_executeHookCallbacks($state_option = null)
    {
        // TODO: Implement fr_executeHookCallbacks() method.
    }

    private function preparePostAnswer($message)
    {
        Env::set("VIEW_TEMPLATE",[ 'CURRENT_TEMPLATE'=>$message]);
        Env::set("VIEW_DATA",[]);
        //Env::set("WRAPPER",0);
    }
}
