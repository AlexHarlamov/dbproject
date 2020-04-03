<?php


namespace app\core\application\warning_informer;

use app\core\application\Application;

class FR_WarningInformer implements Application
{
    public function __construct()
    {
        $err_set_var = false;
        switch (CORE_MODE){
            case MODE_DEV:
                $err_set_var = true;
                break;
            case MODE_PROD:
                $err_set_var = false;
                break;
        }
        ini_set( "display_errors", $err_set_var );
    }
}
