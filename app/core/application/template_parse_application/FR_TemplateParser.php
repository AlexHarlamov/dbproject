<?php


namespace app\core\application\template_parse_application;


use app\core\application\Application;

class FR_TemplateParser implements Application
{

    /**
     * @param $params
     * @return string|string[]
     *
     * Provides simple functionality of injecting variables values into template by name (if variable defined)
     *
     * example 0:
     * $params = ["way" => "left"]
     * $pattern = "we should go to the @way";
     *
     * result: "we should go to the left";
     *
     * example 1:
     * $params = ["way" => "left"]
     * $pattern = "we should go to the @river it is on @way";
     *
     * result: "we should go to the @river it is on left";
     */
    public function simpleInjecting($params){

        $template = $params["template"];
        $vars = $params["vars"];
        $result = $template;

        foreach ($vars as $var => $val){
            $result = str_replace("$var",$val, $result);
        }

        return $result;

    }

}
