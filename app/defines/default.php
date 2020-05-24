<?php
/**
 * @settings_file app/defines/default.php
 *
 * This file all core variables' definitions, be careful editing these values
 *
 */

/**
 * Definition of the hooks positioning variables
 */

define("HOOK_BEFORE", 1);
define("HOOK_AFTER", 2);

/**
 * Definition of the default applications' machine names
 */

define("TEMPLATE_WORKER","fr_template_worker");
define("DATABASE_WORKER","fr_database_worker");
define("WARNING_INFORMER","fr_warning_informer");
define("HOOK_WORKER","fr_hook_worker");
define("REQUEST_PARSER","fr_route_parser");
define("JAVASCRIPT_WORKER","fr_js_worker");
define("HTML_WORKER","fr_html_worker");

/**
 * Definition of the default exit point state name
 */

define("DEFAULT_EXIT_POINT","app\core\state\ExitPoint");

/**
 * Definition of the error messages
 */

define("DEFAULT_APP_REG_ERR", "Core module Init has troubles, application registration failed");

/**
 * Default  templates and elements
 */

define("DEFAULT_CLASS_TEMPLATE_ID","0");
define("NO_ACTION_EXCEPTION_TEMPLATE_ID","1");
define("DEFAULT_CLASS_CHANGE_TEMPLATE_ID","16");
define("DEFAULT_CLASS_CHANGE_ELEMENT_ID","16");
define("DEFAULT_KEYS_TEMPLATE","28");
define("DEFAULT_VALUES_TEMPLATE","28");
define("DEFAULT_THREAD_TEMPLATE","29");
define("DEFAULT_COLS_TEMPLATE","30");
define("DEFAULT_BODY_TEMPLATE","31");
define("DEFAULT_TABLE_TEMPLATE","32");
define("DEFAULT_CLASS_STRUCTURE_TEMPLATE","33");
define("DEFAULT_CLASSES_TEMPLATE","34");

define("DEFAULT_SHOW_ELEMENT","19");
define("DEFAULT_ELEMENT_TEMPLATE_ID","-1");//не реализовано

/**
 * Pattern метки вида @NAME()
 */
define("DEFAULT_VAR_PATTERN","/(?<fTemp>@(?<fName>\w+))\(\)/");

/**
 * Pattern метки вида @function_name(table_name,{"ID":"2"})
 * или @function_name(table_name,[])
 * json format
 */
define("DEFAULT_F1_PATTERN","/(?<fTemp>@(?<fName>\w+))\((?<name>\s*\w+\s*),(?<args>\s*\{\s*\"\w+\"\s*:\s*\"\w+\"\s*(?<tail>,\s*\"\w+\"\s*:\s*\"\w+\"\s*)*\}|\[\])\)/");

/**
 * Pattern метки вида @function_name(id1,id2)
 *
 * id1, id2 - числа!
 */
define("DEFAULT_F2_PATTERN","/(?<fTemp>@(?<fName>\w+))\((?<id1>\s*[0-9]+\s*),(?<id2>\s*[0-9]+\s*)\)/");

/**
 * Default  template means, that current Template node looks like:
 * 0=>new_node1 , 1=>new_node2, ...
 * Спускаясь внутрь получаем готовый шаблон+данные с каждого new_node и потом производим их конкатенацию
 * (пример использования: получили 10 элементов в шаблонах, нужно их соединить)
 *
 * Аргумент - разделитель, который будет вставляться между конкатенацией
 */

define("DEFAULT_CONCATENATION_PATTERN","/(?<fTemp>@(?<fName>DEFAULT_TEMPLATE_CONCATENATION))\(\s*'\s*(?<delimiter>\s*[[:ascii:]]*\s*)'\s*\)/");
/**
 * @keys(tableName)
 */
define("DEFAULT_KEYS_PATTERN","/(?<fTemp>@(?<fName>keys))\((?<tableName>\s*\w+\s*)\)/");
/**
 * @cols()
 */
define("DEFAULT_COLS_PATTERN","/(?<fTemp>@(?<fName>cols))\(\)/");
/**
 * @thread(tableName)
 */
define("DEFAULT_THREAD_PATTERN","/(?<fTemp>@(?<fName>thread))\((?<tableName>\s*\w+\s*)\)/");
/**
 * @classStructure(classID)
 */
define("DEFAULT_CLASS_STRUCTURE_PATTERN","/(?<fTemp>@(?<fName>classStructure))\((?<classID>\s*\w+\s*)\)/");

/**
 * JavaScript files path define
 */

define("DEFAULT_JS_PATH", ROOT_DIR."settings/fr_scripts/src/");
define("REQUEST_JS_PATH", "settings/fr_scripts/src/");

/**
 * Attach JavaScript library regex
 */

define("ATTACH_LIB_REGEX",'/#attach_library\(\"([a-z]\w+)\"\)\;/');

/**
 * Структура сайта
 */
define("SITE_STRUCTURE_ELEMENT",18);
define("SITE_NULL_STRUCTURE_TEMPLATE",9);
define("SITE_HELLO_STRUCTURE_TEMPLATE",18);
define("HELLO_ELEMENT",18);
define("HELLO_TEMPLATE",17);
define("EMPTY_TEMPLATE",21);
/**
 * Пути через obj
 *
 * POST/?OBJ=0
 *
 * GET/OBJ=0
 */
define("POST_CHANGE_CLASS",0);//ответ на GET_CHANGE_CLASS - сохранение и вывод success ли error
define("POST_CHANGE_ELEMENT",1);
define("POST_CHANGE_RELATION",2);
define("POST_CHANGE_LINK",3);

define("GET_CLASSES",0);
define("GET_CLASS",1);
define("GET_ELEMENTS",2);
define("GET_ELEMENT",3);

define("GET_CHANGE_CLASS",4); //получить форму для создания нового или изменения старого (если есть classID)
define("GET_CHANGE_ELEMENT",5);
define("GET_CHANGE_RELATION",6);
define("GET_CHANGE_LINK",7);

define("GET_CLASS_TEMPLATES_ID",-1);//системные вызовы, которые лучше исзменить на более умные шаблоны
define("GET_CLASS_RELATION_TO",-2);//но я пока не придумала таких магических шаблонов
define("GET_CLASS_RELATION_FROM",-3);//боль-печаль


