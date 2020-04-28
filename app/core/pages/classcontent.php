<?php


namespace app\core\pages;


use app\core\util\App;
use app\core\util\Env;

class classcontent implements Page
{

    public static function fr_prepare_data()
    {
        $arr =  App::call(DATABASE_WORKER, "select", [
            "from" => "lemma_classes",
            "what" => [
                "*"
            ],
            "filter" => [
                "ID"
            ],
            "conditions" => [
                Env::get("classid")
            ]
        ]);
        echo "Структура класса:<br />";
        foreach ($arr as $v){
            foreach ($v as $key => $value) {
                echo "| $key = $value ";
            }
        }

        $arr =  App::call(DATABASE_WORKER, "select", [
            "from" => "lemma_attributes",
            "what" => [
                "*"
            ],
            "filter" => [
                "OWNER_CLASS_ID"
            ],
            "conditions" => [
                Env::get("classid")
            ]
        ]);
        echo "<hr>";
        echo "Структура аттрибутов класса:<br />";
        foreach ($arr as $v){
            foreach ($v as $key => $value) {
                echo "| $key = $value ";
            }
        }


        $arr =  App::call(DATABASE_WORKER, "select", [
            "from" => "lemma_relations",
            "what" => [
                "NAME"
            ],
            "filter" => [
                "FROM_CLASS_ID"
            ],
            "conditions" => [
                Env::get("classid")
            ]
        ]);
        echo "<hr>";
        echo "Исходящие из класса связи:<br />";
        foreach ($arr as $v){
            foreach ($v as $key => $value) {
                echo "| $key = $value ";
            }
        }

        $arr =  App::call(DATABASE_WORKER, "select", [
            "from" => "lemma_relations",
            "what" => [
                "NAME"
            ],
            "filter" => [
                "TO_CLASS_ID"
            ],
            "conditions" => [
                Env::get("classid")
            ]
        ]);
        echo "<hr>";
        echo "Входящие в класс связи:<br />";
        foreach ($arr as $v){
            foreach ($v as $key => $value) {
                echo "| $key = $value ";
            }
        }



        echo "<hr>";
        $id = Env::get("classid");
        echo "<a href=\"../listelements?classid=$id\">Посмотреть элементы класса</a><br />";
       // echo "<hr>";
        echo "<a href=\"../listclasses?classid=$id\">Посмотреть все классы</a><br />";
    }

    public static function fr_parse_template()
    {
        // TODO: Implement fr_parse_template() method.
    }
}