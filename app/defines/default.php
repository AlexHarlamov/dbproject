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

