<?php


namespace app\core\application\template_parse_application;


use app\core\application\Application;

class FR_TemplateParser implements Application
{

    public function simpleInjecting($params){

        $template = $params["template"];
        $vars = $params["vars"];

        $result = $template;

        foreach ($vars as $var => $val){
            $result = str_replace("@$var",$val, $result);
        }

        return $result;

    }

}
