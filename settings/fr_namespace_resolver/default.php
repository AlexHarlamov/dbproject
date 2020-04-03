<?php
/**
 * @settings_file namespace_resolver/default.php
 *
 * !!!RED ZONE!!!
 * !!!ATTENTION!!!
 *
 * Override this section if you 100% sure what are you doing and for what reason
 *
 */

spl_autoload_register(function ($class) {

    $fileExtension = '.php';
    $class = str_ireplace('\\','/',$class);
    include ROOT_DIR.$class.$fileExtension;

});
