<?php


namespace app\core\state;


use app\core\CoreState;
use app\core\exception\UndefinedMethodCallException;
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
        if(Env::get("WRAPPER") == 1){
            $this->prepareSiteStructure(Env::get("SSE"),Env::get("SST"));
        }

        try {
            switch (Env::get("FR_ACTION")[0]){
                case "":
                    $this->prepareElementData(HELLO_ELEMENT,HELLO_TEMPLATE);
                    break;
                case "nullInterface":
                    switch (Env::get("FR_ACTION")[1]){
                        case "":
                            //для начальной странички
                            $this->prepareElementData(HELLO_ELEMENT,EMPTY_TEMPLATE);
                            break;
                        case "GET":
                            $this->getHandler();
                            break;
                        case "POST":
                            $this->postHandler();
                            break;
                    }
                    break;
                case "userInterface":
                default :
                    throw new Exception();
            }
        } catch (Exception $e) {
            //TODO: Do not ignore
        }

        return $this->fr_DefaultNextCoreStateAfterPrepareDate;
    }

    /**
     * @throws \app\core\exception\UndefinedEnvVariableException
     *
     * Подготовка любого GET
     */
private function getHandler(){

        if(Env::contains("OBJ")){

            switch (Env::get("OBJ")){
                case GET_CLASSES:
                    $this->prepareGetClasses();
                    break;
                case GET_CLASS:
                    $this->prepareGetClass();
                    break;
                case GET_ELEMENTS:
                    $this->prepareGetElements();
                    break;
                case GET_ELEMENT:
                    if(Env::contains("ELEMENT_ID") ){
                        if(Env::contains("TEMPLATE_ID")){
                            $this->prepareElementData(Env::get("ELEMENT_ID"),Env::get("TEMPLATE_ID"));
                        }
                        else{
                            //TODO:сделать дефолтный шаблон?
                            $this->prepareElementData(Env::get("ELEMENT_ID"),DEFAULT_ELEMENT_TEMPLATE_ID);
                        }
                    }
                    break;
                case GET_CHANGE_CLASS:
                    $this->prepareClassToChange();
                    break;
                case GET_CHANGE_ELEMENT:
                    break;
                case GET_CLASS_TEMPLATES_ID:
                    $this->prepareTemplatesList();
                    break;
                case GET_CLASS_RELATION_TO:
                    $this->prepareClassRelations("FROM_CLASS_ID","TO_CLASS_ID");
                    break;
                case GET_CLASS_RELATION_FROM:
                    $this->prepareClassRelations("TO_CLASS_ID","FROM_CLASS_ID");
                    break;
                default:
                    throw new Exception();
            }
        }
        else{
            throw new Exception();
        }
}

    /**
     * @throws \app\core\exception\UndefinedEnvVariableException
     *
     * Подготовка любого POST
     */
private function postHandler(){

    if(Env::contains("OBJ")){

        switch (Env::get("OBJ")){
            case POST_CHANGE_CLASS:
                if(Env::contains("FR_FORM_DATA_TO_SAVE")){
                    $message = $this->saveNewClass();
                    $this->preparePostAnswer($message);
                }
                break;
            case POST_CHANGE_ELEMENT:
                break;
            case POST_CHANGE_RELATION:
                break;
            case POST_CHANGE_LINK:
                break;
            default:
                throw new Exception();
        }
    }else{
        throw new Exception();
    }

}

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
     * Получить данные и шаблон для элемента
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
     * Получить данные и шаблон для оболочки сайта (навигация, кнопки...)
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

    /**
     * Получить данные и шаблон для создания класса
     * По-идее тут должно быть реализовано еще и редактирование класса, если задан classID
     */
private function prepareClassToChange(){
        $this->prepareElementData(DEFAULT_CLASS_CHANGE_ELEMENT_ID,DEFAULT_CLASS_CHANGE_TEMPLATE_ID);
    }

    /**
     * @return string
     * @throws \app\core\exception\UndefinedEnvVariableException
     *
     * Создает новый класс, по полученным данным из формы после нажатия кнопки submit
     */
private function saveNewClass(){
        $message = null;

        $data = Env::get("FR_FORM_DATA_TO_SAVE");

        $classID = null;

        if(isset($data["ClassName"]) && $data["ClassName"]!=""){

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
                return $e->getMessage();
            } catch (UndefinedMethodCallException $e) {
            } catch (Exception $e) {
                //TODO:error
            }

            if(empty($arr)){
                return "no data";
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
                        return $e->getMessage();
                    } catch (UndefinedMethodCallException $e) {
                    } catch (Exception $e) {
                        return $e->getMessage();
                    }

                    if(empty($arr)){
                        return "no data";
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
                    return $e->getMessage();
                } catch (UndefinedMethodCallException $e) {
                } catch (Exception $e) {
                    //TODO:error
                }

                if(empty($arr)){
                    return "no data";
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

    /**
     * @param $message
     * Подготовка ответа на попытку сохранить-изменить данные через форму,
     * ответ будет некой ошибкой либо success
     */
private function preparePostAnswer($message)
    {
        Env::set("VIEW_TEMPLATE",[ 'CURRENT_TEMPLATE'=>$message]);
        Env::set("VIEW_DATA",[]);
    }

    /**
     * @throws \app\core\exception\UndefinedEnvVariableException
     *
     * Получить на просмотр Класс
     */
private function prepareGetClass(){

        $classID = Env::get("CLASS_ID");

        $keyR = '@classStructure('.$classID.')';
        $result = classStructPrepare($classID,$keyR);

        $templateNode['CURRENT_TEMPLATE'] = $keyR;
        $templateNode[$result['tempK']] = $result['tempV'];
        $dataNode[$result['dataK']] = $result['dataV'];

        Env::set("VIEW_TEMPLATE",$templateNode);
        Env::set("VIEW_DATA",$dataNode);
    }

    /**
     * Получить на просмотр всю таблицу lemma_classes
     */
private function prepareGetClasses(){
        $keyR = '@table(lemma_classes,[])';

        $currentTemplate = getTemplate(DEFAULT_CLASSES_TEMPLATE);

        $result = tablePrepare("lemma_classes","[]",$keyR);

        $templateNode['CURRENT_TEMPLATE'] = $currentTemplate;
        $templateNode[$result['tempK']] = $result['tempV'];
        $dataNode[$result['dataK']] = $result['dataV'];

        Env::set("VIEW_TEMPLATE",$templateNode);
        Env::set("VIEW_DATA",$dataNode);
    }

    /**
     * @throws \app\core\exception\UndefinedEnvVariableException
     * Получить на просмотр Элементы конкретного Класса, вывод таблицы Класса
     */
private function prepareGetElements(){
        $tableName = getClassTable(Env::get("CLASS_ID"));
        $keyR = "@table($tableName,[])";

        $result = tablePrepare($tableName,"[]",$keyR);

        $str = "#attach_library(\"addButtonsListClassElements\");\r\n";

        $templateNode['CURRENT_TEMPLATE'] = $str.$keyR;
        $templateNode[$result['tempK']] = $result['tempV'];
        $dataNode[$result['dataK']] = $result['dataV'];

        Env::set("VIEW_TEMPLATE",$templateNode);
        Env::set("VIEW_DATA",$dataNode);
    }

    /**
     * @throws \app\core\exception\UndefinedEnvVariableException
     * Системная функция для получения списка шаблонов
     */
private function prepareTemplatesList(){

        if(Env::contains("CLASS_ID")){

            $str = getTemplateIds(Env::get("CLASS_ID"));

            Env::set("VIEW_TEMPLATE",["CURRENT_TEMPLATE"=>$str]);
            Env::set("VIEW_DATA",[]);

        }else{
            throw new Exception();
        }

    }

    /**
     * @param $needSide
     * @param $haveSide
     * @throws \app\core\exception\UndefinedEnvVariableException
     *
     * Системная функция для получения списка Отношений
     */
private function prepareClassRelations($needSide, $haveSide){

        if(Env::contains("CLASS_ID")){

            $str = getElementLinksId(Env::get("CLASS_ID"),$needSide,$haveSide);

            Env::set("VIEW_TEMPLATE",["CURRENT_TEMPLATE"=>$str]);
            Env::set("VIEW_DATA",[]);

        }else{
            throw new Exception();
        }

    }
}
