<?php

/**
 * @file settings.php
 *
 * this file contains all the default definitions and configurations
 *
 */

use app\core\application\database\FR_DatabaseWorker;
use app\core\application\template_parse_application\FR_TemplateParser;
use app\core\application\warning_informer\FR_WarningInformer;

/**
 * Definition section
 *
 * here you have to define all the requirements for applications
 *
 * if you overrides one of the application be sure that their configurators backward compatible
 *
 */

$DEFAULT_DATABASE_CONFIGURATION = "fr_database/default.php";

require_once $DEFAULT_DATABASE_CONFIGURATION;

/**
 * Available core modes:
 *
 * $CORE_MODE_DEVELOPMENT
 *  development mode - critical exceptions will stop execution with printing stacktrace and error;
 *
 * $CORE_MODE_PRODUCTION
 *  production mode - system will try to not stop execution or will display error page if fatal error handled;
 *
*/

$CORE_MODE_DEVELOPMENT = 0;
$CORE_MODE_PRODUCTION = 1;
define("MODE_DEV", $CORE_MODE_DEVELOPMENT);
define("MODE_PROD", $CORE_MODE_PRODUCTION);

// to change mode replace second argument of the define function
define("CORE_MODE", $CORE_MODE_DEVELOPMENT);

/**
 * Application registration section
 *
 * Here you can register your own new applications or override existing ones
 *
 * Default applications names:
 *
 * TEMPLATE_WORKER
 * DATABASE_WORKER
 * WARNING_INFORMER
 * HOOK_WORKER
 *
 * If you want to add new application then define it's machine name
 * and export it's exemplar, follow these rules of naming:
 *
 *  define("APPLICATION_NAME","cs_application_name_author");
 *
 * APPLICATION NAME should be unique
 *
 *
 */

// to override application just replace instantiation of the class
$DEFAULT_TEMPLATE_PARSE_APPLICATION = new FR_TemplateParser();
$DEFAULT_DATABASE_WORK_APPLICATION = new FR_DatabaseWorker();
$DEFAULT_WARNING_INFORMER = new FR_WarningInformer();


$this->fr_exportApplications([
    TEMPLATE_WORKER => $DEFAULT_TEMPLATE_PARSE_APPLICATION,
    DATABASE_WORKER => $DEFAULT_DATABASE_WORK_APPLICATION,
    WARNING_INFORMER => $DEFAULT_WARNING_INFORMER
]);

/**
 * JavaScript attachment section
 */
