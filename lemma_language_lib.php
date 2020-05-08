<?php

use app\core\exception\UndefinedApplicationCallException;
use app\core\exception\UndefinedMethodCallException;
use app\core\exception\UndefinedTemplateException;
use app\core\util\App;

/**
 * @param $templateId
 * @return mixed
 *
 * Returns body of template by it's id
 */
function getTemplate($templateId){
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
        if (empty($arr) && !isset(($arr[0])["BODY"])) {
            //TODO:Handle
            throw  new UndefinedTemplateException("template with id $templateId does not exist");
        }else{
            return ($arr[0])["BODY"];
        }
    } catch (UndefinedApplicationCallException $e) {
    } catch (UndefinedMethodCallException $e) {
    } catch (Exception $e) {
        //TODO:Handle
    }
    return null;
}

/**
 * @param $elementId
 * @return mixed|null
 *
 * Returns classID by elementID
 */
function getClassIdByElement($elementId){

    try {
        $arr = App::call(DATABASE_WORKER, "select", [
            "from" => "lemma_elements",
            "what" => [
                "OWNER_CLASS_ID"
            ],
            "filter" => [
                "ID"
            ],
            "conditions" => [
                $elementId
            ]
        ]);

        if(empty($arr) && !isset(($arr[0])["OWNER_CLASS_ID"])){
            //TODO:handle
            return null;
        }
        else{
            return ($arr[0])["OWNER_CLASS_ID"];
        }

    } catch (UndefinedApplicationCallException $e) {
    } catch (UndefinedMethodCallException $e) {
    } catch (Exception $e) {
        //TODO:handle
    }
    return null;
}

/**
 * @param $classId
 * @return string|null
 *
 * Returns tableName of class, where it's elements are
 */
function getClassTable($classId){

    try {
        $arr = App::call(DATABASE_WORKER, "select", [
            "from" => "lemma_classes",
            "what" => [
                "I_NAME"
            ],
            "filter" => [
                "ID"
            ],
            "conditions" => [
                $classId
            ]
        ]);
        if(empty($arr) && !isset(($arr[0])["I_NAME"])){
            //TODO:handle
            return null;
        }
        else{
            return "class_".($arr[0])["I_NAME"];
        }

    } catch (UndefinedApplicationCallException $e) {
    } catch (UndefinedMethodCallException $e) {
    } catch (Exception $e) {
        //TODO:handle
    }
    return null;
}

/**
 * @param $str
 * @param $currentData
 * @return array
 *
 *Recursion using marks to prepare data
 */
function recursionPrepare($str, $currentData){

    /*variable*/
    preg_match(DEFAULT_VAR_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL);
    if(!empty($matches) && isset($currentData[$matches['fName']])){
        return variablePrepare($matches[0],$currentData[$matches['fName']]);
    }

    /*function f1*/
    preg_match(DEFAULT_F1_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL);
    if(!empty($matches)){
        //TODO:realization
        return null;
    }

    /*function f2*/
    preg_match(DEFAULT_F2_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL);
    if(!empty($matches) && isset($matches['id1']) && isset($matches['id2']) ){
        switch ($matches['fName']){
            case 'element':
                return elementPrepare($matches[0],$matches['id1'],$matches['id2']);
            case 'class':
                return classPrepare($matches[0],$matches['id1'],$matches['id2']);
            default:
                return null;
        }
    }

    /*function concatenation*/
    preg_match(DEFAULT_CONCATENATION_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL);
    if(!empty($matches)){
        //TODO:realization
        return null;
    }
    
    return null;
}

/**
 * @param $varName
 * @param $varValue
 * @return array
 * Prepare data for @NAME()
 */
function variablePrepare($varName, $varValue){

    return ['tempK' => $varName ,
            'tempV' => '',
            'dataK' => $varName ,
            'dataV' => $varValue
           ];
}

/**
 * @param $key
 * @param $elementId
 * @param $templateId
 * @return array|null
 *
 * Prepare data for @element(element_id,template_id)
 */
function elementPrepare($key,$elementId,$templateId){

    /*Получаем из базы данных шаблон по ID*/
    $currentTemplate = getTemplate($templateId);
    
    $classId = getClassIdByElement($elementId);
    $table = getClassTable($classId);
    $currentData = null;

    /*Получаем из базы данных данные об элементе ID*/
    try{
        $arr =  App::call(DATABASE_WORKER, "select", [
            "from" => $table,
            "what" => [
                "*"
            ],
            "filter" => [
                "OWNER_ELEMENT_ID"
            ],
            "conditions" => [
                $elementId
            ]
        ]);
        if(!empty($arr)){
            $currentData = $arr[0];
        }else{
            //TODO:handle
            return null;
        }
    }catch (UndefinedApplicationCallException $e) {
    } catch (UndefinedMethodCallException $e) {
    } catch (Exception $e) {
        //TODO:handle
        return null;
    }

    /*Подготовка переменных*/
    $templateNode = ['CURRENT_TEMPLATE'=> $currentTemplate];
    $dataNode = [];
    $str = $currentTemplate;
    $pos = strpos($str, '@');
    while($pos !== false){
        $str = substr($str, $pos);
        $subtree = recursionPrepare($str,$currentData);
        if($subtree != null){
            $templateNode[$subtree['tempK']] = $subtree['tempV'];
            $dataNode[$subtree['dataK']] = $subtree['dataV'];
        }
        $str = substr($str, 1);
        $pos = strpos($str, '@');
    }

    return ['tempK' => $key,
        'tempV' => $templateNode,
        'dataK' => $key,
        'dataV' => $dataNode
    ];

}

/**
 * @param $key
 * @param $classId
 * @param $templateId
 * @return array|null
 *
 * Prepare data for @class(class_id,class_id)
 */
function classPrepare($key,$classId,$templateId){

    /*Получаем из базы данных шаблон по ID*/
    $currentTemplate = getTemplate($templateId);

    /*Получаем из базы данных данные класс по ID*/
    try{
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
        if(!empty($arr)){
            $currentData = $arr[0];
        }else{
            //TODO:handle
            return null;
        }
    }catch (UndefinedApplicationCallException $e) {
    } catch (UndefinedMethodCallException $e) {
    } catch (Exception $e) {
        //TODO:handle
        return null;
    }

    /*Подготовка переменных*/
    $templateNode = ['CURRENT_TEMPLATE'=> $currentTemplate];
    $dataNode = [];
    $str = $currentTemplate;
    $pos = strpos($str, '@');
    while($pos !== false){
        $str = substr($str, $pos);
        $subtree = recursionPrepare($str,$currentData);
        if($subtree != null){
            $templateNode[$subtree['tempK']] = $subtree['tempV'];
            $dataNode[$subtree['dataK']] = $subtree['dataV'];
        }
        $str = substr($str, 1);
        $pos = strpos($str, '@');
    }

    return ['tempK' => $key,
        'tempV' => $templateNode,
        'dataK' => $key,
        'dataV' => $dataNode
    ];

}


/**
 * @param $template
 * @param $data
 * @return mixed
 *
 * Рекурсия для соединения шаблона и данных
 *
 */
function recursionParseLoop($template,$data){
    /*Подготовка переменных*/
    $currentTemplate = $template['CURRENT_TEMPLATE'];
    $currentData = $data;

    $str = $currentTemplate;
    $pos = strpos($str, '@');

    while($pos !== false){
        $str = substr($str, $pos);
        $matches = null;

        /*variable*/
        if(1 == preg_match(DEFAULT_VAR_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL)){
            if(!empty($matches) && isset($currentData[$matches[0]])){
                $pos = strpos($str, $matches[0]);
                $currentTemplate = variableParse($currentTemplate,$currentData);
            }

        /*function f1*/
        }elseif (1 == preg_match(DEFAULT_F1_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL)){

            if(!empty($matches)){
                //TODO:realization
            }

         /*function concatenation*/
        }elseif (1 == preg_match(DEFAULT_CONCATENATION_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL)){

            if(!empty($matches)){
                //TODO:realization
            }

        /*function f2*/
        }elseif(1 == preg_match(DEFAULT_F2_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL)){

            if(!empty($matches) && isset($matches['id1']) && isset($matches['id1']) ){
                $pos = strpos($str, $matches[0]);
                switch ($matches['fName']){
                    case 'element':
                        $currentTemplate =  elementParse($template,$currentData[$matches[0]],$matches[0]);
                        break;
                    case 'class':
                        $currentTemplate =  classParse($template,$currentData[$matches[0]],$matches[0]);
                        break;
                    default:
                        //TODO:handle
                }
            }

        }else{
            //чтоб при нахождении всяких ab@c не было цикла
            $pos++;
        }

        $str = $currentTemplate;
        $str = substr($str, $pos);
        $template['CURRENT_TEMPLATE'] = $currentTemplate;
        $pos = strpos($str, '@');
    }
    return $currentTemplate;
}

/**
 * @param $currentTemplate
 * @param $currentData
 * @return mixed|null
 */
function variableParse($currentTemplate,$currentData){

    $t = null;
    try {
        $t = App::call(TEMPLATE_WORKER, "simpleInjecting", [
            "template" => $currentTemplate,
            "vars" => refactorArray($currentData)
        ]);
    }catch (UndefinedApplicationCallException $e) {
    } catch (UndefinedMethodCallException $e) {
    } catch (Exception $e) {
        //TODO:Handle
    }
    return $t;
}

/**
 * @param $parentTemplate
 * @param $currentData
 * @param $function
 * @return mixed|null
 */
function elementParse($parentTemplate,$currentData,$function){

    $currentTemplate = $parentTemplate[$function];
    $result = recursionParseLoop($currentTemplate,$currentData);
    $t = null;
    try{
        $t = App::call(TEMPLATE_WORKER, "simpleInjecting", [
        "template" => $parentTemplate['CURRENT_TEMPLATE'],
        "vars" => [
            $function => $result
        ]
    ]);
    }catch (UndefinedApplicationCallException $e) {
    } catch (UndefinedMethodCallException $e) {
    } catch (Exception $e) {
        //TODO:Handle
    }
    return $t;
}

/**
 * @param $parentTemplate
 * @param $currentData
 * @param $function
 * @return mixed|null
 */
function classParse($parentTemplate,$currentData,$function){

    $currentTemplate = $parentTemplate[$function];
    $result = recursionParseLoop($currentTemplate,$currentData);
    $t = null;

    try {
        $t =  App::call(TEMPLATE_WORKER, "simpleInjecting", [
            "template" => $parentTemplate['CURRENT_TEMPLATE'],
            "vars" => [
                $function => $result
            ]
        ]);
    }catch (UndefinedApplicationCallException $e) {
    } catch (UndefinedMethodCallException $e) {
    } catch (Exception $e) {
        //TODO:Handle
    }
    return $t;
}


/**
 * @param $arr
 * @return array
 *
 * Отделяем данные конкретного уровня от вложенных
 */
function refactorArray($arr){

    $newArr = [];

    foreach ($arr as $key => $val){
        if(!is_array($val)){
            $newArr[$key] = $val;
        }
    }

    return $newArr;
}