<?php

require_once "globals/db_config.php";

ini_set( "display_errors", true );

spl_autoload_register(function ($class) {
    $class = str_ireplace('\\','/',$class);
    include 'app/'.$class.'.php';
});
