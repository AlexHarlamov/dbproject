<?php

/**
 * @file settings.php
 *
 * this file contains all the default definitions and configurations
 *
 */

use app\core\application\database\FR_DatabaseWorker;
use app\core\application\html_configurator\FR_HTMLConfigurator;
use app\core\application\javascript_worker\FR_JavaScriptIntegrator;
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
$DEFAULT_JAVASCRIPT_INTEGRATOR = new FR_JavaScriptIntegrator();
$DEFAULT_HTML_CONSTRUCTOR = new FR_HTMLConfigurator();


$this->fr_exportApplications([
    TEMPLATE_WORKER => $DEFAULT_TEMPLATE_PARSE_APPLICATION,
    DATABASE_WORKER => $DEFAULT_DATABASE_WORK_APPLICATION,
    WARNING_INFORMER => $DEFAULT_WARNING_INFORMER,
    JAVASCRIPT_WORKER => $DEFAULT_JAVASCRIPT_INTEGRATOR,
    HTML_WORKER => $DEFAULT_HTML_CONSTRUCTOR
]);

/**
 * JavaScript attachment section
 *
 * If you have some JavaScript for integration just put your .js file into /fr_scripts/src
 * and register it using $DEFAULT_JAVASCRIPT_INTEGRATOR->registerScript("script_name.js", "machine_name")
 * then just use it in the templates using @attach_script("machine_name")
 *
 * Machine and .js name should be unique
 */

$DEFAULT_JAVASCRIPT_INTEGRATOR->registerScript('1.js',"hello");
$DEFAULT_JAVASCRIPT_INTEGRATOR->registerScript('2.js',"fullClassEditor");
