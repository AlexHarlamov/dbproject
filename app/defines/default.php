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

/**
 * Definition of the default exit point state name
 */

define("DEFAULT_EXIT_POINT","app\core\state\ExitPoint");

/**
 * Definition of the error messages
 */

define("DEFAULT_APP_REG_ERR", "Core module Init has troubles, application registration failed");

/**
 * Default classes' template
 */

define("DEFAULT_CLASS_TEMPLATE_ID","0");

/**
 * Default exceptions' templates
 */

define("NO_ACTION_EXCEPTION_TEMPLATE_ID","1");

/**
 * Default element' template
 */

define("DEFAULT_ELEMENT_TEMPLATE_ID","2");

/**
 * Pattern метки вида @NAME()
 */
define("DEFAULT_VAR_PATTERN","/(?<fTemp>@(?<fName>\w+))\(\)/");

/**
 * Pattern метки вида @function_name(table_name,['1' => 'ID','2' => 'SMTH',...])
 */
define("DEFAULT_F1_PATTERN","/(?<fTemp>@(?<fName>\w+))\((?<name>\s*\w+\s*),(?<args>\s*\[\s*'\w+'\s*=>\s*'\w+'\s*(?<tail>,\s*'\w+'\s*=>\s*'\w+'\s*)*\])\)/");

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
