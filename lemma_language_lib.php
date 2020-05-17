<?php

use app\core\exception\DataBaseException;
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
            throw  new DataBaseException("");
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
            throw  new DataBaseException("");
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

    /*function thread*/
    preg_match(DEFAULT_THREAD_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL);
    if(!empty($matches)){
        return threadPrepare($matches["tableName"]);
    }


    /*function f1*/
    preg_match(DEFAULT_F1_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL);
    if(!empty($matches)){
        switch ($matches['fName']){
            case 'rows':
                return rowsPrepare($matches["name"],$matches["args"],$matches[0]);
            case 'body':
                return bodyPrepare($matches["name"],$matches["args"],$matches[0]);
            case 'table':
                return tablePrepare($matches["name"],$matches["args"],$matches[0]);
            default:
                return null;
        }
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

    /*function keys*/
    preg_match(DEFAULT_KEYS_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL);
    if(!empty($matches)){
        return keysPrepare($matches["tableName"]);
    }


    return null;
}
/*
 * Prepare for @keys(tableName) -- нет в какой либо рекурсии, так как сразу заменяется
 */
function keysPrepare($tableName){

    try{
        $tableDesc =  App::call(DATABASE_WORKER, "describeTable",$tableName);
    }catch (PDOException $e) {
    } catch (UndefinedMethodCallException $e) {
    } catch (Exception $e) {
        //TODO:error
    }

    $currentTemplate = getTemplate(DEFAULT_KEYS_TEMPLATE);
    $resultTemplate = null;

    foreach ($tableDesc as $val){
        $resultTemplate.=str_replace("@NAME()",$val["Field"],$currentTemplate);
    }

    return ['tempK' => "@KEYS($tableName)" ,
        'tempV' => $resultTemplate,
        'dataK' => "@KEYS($tableName)" ,
        'dataV' => []
    ];
}

function threadPrepare($tableName){

    $currentTemplate = getTemplate(DEFAULT_THREAD_TEMPLATE);
    $currentTemplate = str_replace("@NAME()",$tableName,$currentTemplate);
    $subtree = recursionPrepare($currentTemplate,null);
    if($subtree != null){
        $templateNode[$subtree['tempK']] = $subtree['tempV'];
    }

    $currentTemplate = str_replace("@keys($tableName)",$subtree['tempV'],$currentTemplate);

    return ['tempK' => "@thread($tableName)",
        'tempV' => ['CURRENT_TEMPLATE'=> $currentTemplate],
        'dataK' => "@thread($tableName)",
        'dataV' => []
    ];
}
/*
 * Prepare for @rows(name,{"a":"b","c":"d"})
 */
function rowsPrepare($tableName,$aF,$keyR){

    $arrFiles =  json_decode($aF, true);

    try{
        $arr = null;
        if(empty($arrFiles)){
            $arr =  App::call(DATABASE_WORKER, "select", [
                "from" => $tableName,
                "what" => [
                    "*"
                ]
            ]);
        }else{
            $arr =  App::call(DATABASE_WORKER, "select", [
                "from" => $tableName,
                "what" => [
                    "*"
                ],
                "filter" => array_keys($arrFiles),
                "conditions" => array_values($arrFiles)
            ]);
        }
        if(empty($arr)){
            throw  new DataBaseException("");
        }
    }catch (UndefinedApplicationCallException $e) {
    } catch (UndefinedMethodCallException $e) {
    } catch (Exception $e) {
        //TODO:handle
        return null;
    }

    $nextTemplate = getTemplate(DEFAULT_COLS_TEMPLATE);

    $ResT["CURRENT_TEMPLATE"] = "@DEFAULT_TEMPLATE_CONCATENATION('')";
    $ResD = null;

    foreach ($arr as $key => $value){
        $cT=null;
        $cD=null;

        $r = colsPrepare($value);
        $cT["CURRENT_TEMPLATE"] = $nextTemplate;
        $cT["@cols()"] = $r["tempV"];

        $cD["@cols()"] = $r["dataV"];

        $ResT[$key] = $cT;
        $ResD[$key] = $cD;
    }

    return ['tempK' => $keyR ,
        'tempV' => $ResT,
        'dataK' => $keyR ,
        'dataV' => $ResD
    ];

}
/*
 * Prepare for @cols() -- нет в рекурсии prepare, так как всегда идет в связке с rows
 */
function colsPrepare($row){
    $currentTemplate = getTemplate(DEFAULT_VALUES_TEMPLATE);

    $resT["CURRENT_TEMPLATE"] = "@DEFAULT_TEMPLATE_CONCATENATION('')";
    $resD = null;

    $i = 0;
    foreach ($row as $k=>$v){
        $cT=null;
        $cD=null;

        $cT["CURRENT_TEMPLATE"] = str_replace("@NAME()","@".$k."()",$currentTemplate);
        $cT["@".$k."()"] = '';

        $cD["@".$k."()"] = $v;

        $resT[$i] = $cT;
        $resD[$i] = $cD;
        $i++;
    }

    return [
        'tempV' => $resT,
        'dataV' => $resD
    ];
}
/*
 * Prepare for @body(name,{"a":"b","c":"d"})
 */
function bodyPrepare($tableName,$args,$keyR){
    //args were json_encode()

    $currentTemplate = getTemplate(DEFAULT_BODY_TEMPLATE);
    $currentTemplate = str_replace("@NAME()",$tableName,$currentTemplate);
    $currentTemplate = str_replace("@ARGS()",$args,$currentTemplate);

    $subtree = recursionPrepare($currentTemplate,[]);

    $templateNode = ['CURRENT_TEMPLATE'=> $currentTemplate];
    $dataNode = null;

    if($subtree != null){
        $templateNode[$subtree['tempK']] = $subtree['tempV'];
        $dataNode[$subtree['dataK']] = $subtree['dataV'];
    }

    return ['tempK' => $keyR,
        'tempV' => $templateNode,
        'dataK' => $keyR,
        'dataV' => $dataNode
    ];
}
/*
 * Prepare for @table(name,{"a":"b","c":"d"})
 */
function tablePrepare($tableName,$args,$keyR){
    $currentTemplate = getTemplate(DEFAULT_TABLE_TEMPLATE);
    $currentTemplate = str_replace("@NAME()",$tableName,$currentTemplate);
    $currentTemplate = str_replace("@ARGS()",$args,$currentTemplate);

    $templateNode = ['CURRENT_TEMPLATE'=> $currentTemplate];
    $dataNode = [];

    $str = $currentTemplate;
    $pos = strpos($str, '@');

    while($pos !== false){
        $str = substr($str, $pos);
        $subtree = recursionPrepare($str,[]);
        if($subtree != null){
            $templateNode[$subtree['tempK']] = $subtree['tempV'];
            $dataNode[$subtree['dataK']] = $subtree['dataV'];
        }
        $str = substr($str, 1);
        $pos = strpos($str, '@');
    }

    return ['tempK' => $keyR,
        'tempV' => $templateNode,
        'dataK' => $keyR,
        'dataV' => $dataNode
    ];
}
/**
 * Prepare data for @classStructure(classID)
 */
function classStructPrepare($classID,$keyR){
    $currentTemplate = getTemplate(DEFAULT_CLASS_STRUCTURE_TEMPLATE);
    $currentTemplate = str_replace("@ID()",$classID,$currentTemplate);

    $templateNode = ['CURRENT_TEMPLATE'=> $currentTemplate];
    $dataNode = [];

    $str = $currentTemplate;
    $pos = strpos($str, '@');

    while($pos !== false){
        $str = substr($str, $pos);
        $subtree = recursionPrepare($str,[]);
        if($subtree != null){
            $templateNode[$subtree['tempK']] = $subtree['tempV'];
            $dataNode[$subtree['dataK']] = $subtree['dataV'];
        }
        $str = substr($str, 1);
        $pos = strpos($str, '@');
    }

    return ['tempK' => $keyR,
        'tempV' => $templateNode,
        'dataK' => $keyR,
        'dataV' => $dataNode
    ];
}
/**
 * Prepare data for @element(element_id,template_id)
 */
function elementsPrepare($elementId,$templateId,$key){

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
            throw  new DataBaseException("");
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
            throw  new DataBaseException("");
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

//----------------------------------------------------------------------------------------------------------------------
/**
 * Рекурсия для соединения шаблона и данных
 */
function recursionParseLoop($template,$data){

    /*Подготовка переменных*/
    $currentTemplate = $template['CURRENT_TEMPLATE'];
    $currentData = $data;

    $str = $currentTemplate;
    $pos = strpos($str, '@');
    $allPos = 0;

    while($allPos<strlen($currentTemplate) && $pos !== false){
        $str = substr($str, $pos);
        $matches = null;

        /*function cols*/
        if(1 == preg_match(DEFAULT_COLS_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL)){
            if(!empty($matches)){
                $pos = strpos($str, $matches[0]);
                $currentTemplate = colsParse($template,$currentData[$matches[0]],$matches[0]);
            }

        /*variable*/
        }elseif(1 == preg_match(DEFAULT_VAR_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL)){
            if(!empty($matches) && isset($currentData[$matches[0]])){
                $pos = strpos($str, $matches[0]);
                $currentTemplate = variableParse($currentTemplate,$currentData);
            }

        /*function thread*/
        }elseif (1 == preg_match(DEFAULT_THREAD_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL)){

            if(!empty($matches)){
                $pos = strpos($str, $matches[0]);
                $currentTemplate = threadParse($template,$matches[0]);
            }

        /*function classStructure*/
        }elseif (1 == preg_match(DEFAULT_CLASS_STRUCTURE_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL)){

            if(!empty($matches) && isset($currentData[$matches[0]]) ){
                $pos = strpos($str, $matches[0]);
                $currentTemplate = classStructureParse($template,$currentData[$matches[0]],$matches[0]);

            }

            /*function f1*/
        }elseif (1 == preg_match(DEFAULT_F1_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL)){

            if(!empty($matches) && isset($currentData[$matches[0]])){
                $pos = strpos($str, $matches[0]);
                switch ($matches['fName']){
                    case 'rows':
                        $currentTemplate = rowsParse($template,$currentData[$matches[0]],$matches[0]);
                        break;
                    case 'body':
                        $currentTemplate = bodyParse($template,$currentData[$matches[0]],$matches[0]);
                        break;
                    case 'table':
                        $currentTemplate = tableParse($template,$currentData[$matches[0]],$matches[0]);
                        break;
                    default:
                        return null;
                }
            }

        /*function concatenation*/
        }elseif (1 == preg_match(DEFAULT_CONCATENATION_PATTERN,$str,$matches, PREG_UNMATCHED_AS_NULL)){
            if(!empty($matches)){
                $currentTemplate = concatenationParse($template,$currentData,$matches["delimiter"]);
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

        $allPos +=$pos;
        $str = $currentTemplate;
        $str = substr($str, $allPos);
        $template['CURRENT_TEMPLATE'] = $currentTemplate;
        $pos = strpos($str, '@');
    }
    return $currentTemplate;
}

function classStructureParse($parentTemplate,$currentData,$function){
    return sameFunctionParseCode($parentTemplate,$currentData,$function);
}

function tableParse($parentTemplate,$currentData,$function){
    return sameFunctionParseCode($parentTemplate,$currentData,$function);
}

function threadParse($parentTemplate,$function){
    $currentTemplate = ($parentTemplate[$function])['CURRENT_TEMPLATE'];
    $t = null;
    try{
        $t = App::call(TEMPLATE_WORKER, "simpleInjecting", [
            "template" => $parentTemplate['CURRENT_TEMPLATE'],
            "vars" => [
                $function => $currentTemplate
            ]
        ]);
    }catch (UndefinedApplicationCallException $e) {
    } catch (UndefinedMethodCallException $e) {
    } catch (Exception $e) {
        //TODO:Handle
    }
    return $t;
}

function bodyParse($parentTemplate,$currentData,$function){
    return sameFunctionParseCode($parentTemplate,$currentData,$function);
}

function rowsParse($parentTemplate,$currentData,$function){
    return sameFunctionParseCode($parentTemplate,$currentData,$function);
}

function colsParse($parentTemplate,$currentData,$function){
    return sameFunctionParseCode($parentTemplate,$currentData,$function);
}

function concatenationParse($parentTemplate, $currentData,$delimiter){

    $i = 0;
    $t = null;
    while(isset($parentTemplate[$i]) && isset($currentData[$i])){
        $t= $t.$delimiter.recursionParseLoop($parentTemplate[$i],$currentData[$i]);
        $i++;
    }

    return $t;
}

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

function elementParse($parentTemplate,$currentData,$function){
    return sameFunctionParseCode($parentTemplate,$currentData,$function);
}

function classParse($parentTemplate,$currentData,$function){
    return sameFunctionParseCode($parentTemplate,$currentData,$function);
}

function sameFunctionParseCode($parentTemplate,$currentData,$function){
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