<?php


namespace app\core\pages;


use app\core\util\App;
use app\core\util\Env;

class elementintemplate implements Page
{

    public static function fr_prepare_data()
    {
        $arr =  App::call(DATABASE_WORKER, "select", [
            "from" => "lemma_elements",
            "what" => [
                "OWNER_CLASS_ID"
            ],
            "filter" =>[
                "ID"
            ],
            "conditions" => [
                Env::get("elementid")
            ]
        ]);
        $classid = ($arr[0])["OWNER_CLASS_ID"];

        $arr =  App::call(DATABASE_WORKER, "select", [
            "from" => "lemma_classes",
            "what" => [
                "I_NAME"
            ],
            "filter" =>[
                "ID"
            ],
            "conditions" => [
                $classid
            ]
        ]);
        $table = "class_".($arr[0])["I_NAME"];

        $arr =  App::call(DATABASE_WORKER, "select", [
            "from" => $table,
            "what" => [
                "*"
            ],
            "filter" => [
                "OWNER_ELEMENT_ID"
            ],
            "conditions" => [
                Env::get("elementid")
            ]
        ]);
        Env::set("element",$arr[0]);
        //print_r($arr[0]);

        $arr =  App::call(DATABASE_WORKER, "select", [
            "from" => "lemma_templates",
            "what" => [
                "BODY"
            ],
            "filter" => [
                "OWNER_CLASS_ID",
                "ID"
            ],
            "conditions" => [
                $classid,
                Env::get("templateid")
            ]
        ]);
        Env::set("template",($arr[0])["BODY"]);


    }

    public static function fr_parse_template()
    {
        try {
            echo App::call(TEMPLATE_WORKER, "simpleInjecting", [
                "template" => Env::get("template"),
                "vars" => Env::get("element")
            ]);

        } catch (Exception $exception){
            echo $exception;
        }
    }
}