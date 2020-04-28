<?php


namespace app\core\pages;

use app\core\util\App;


class listclasses implements Page
{

    public static function fr_prepare_data()
    {
        $arr =  App::call(DATABASE_WORKER, "select", [
            "from" => "lemma_classes",
            "what" => [
                "ID",
                "NAME"
            ]
        ]);
        foreach ($arr as $v){
            foreach ($v as $key => $value) {
                echo "| $key = $value ";
            }
            $id = $v["ID"];
            echo "<a href=\"../classcontent?classid=$id\">Посмотреть класс подробно</a><br />";
        }

    }

    public static function fr_parse_template()
    {
        // TODO: Implement fr_parse_template() method.
    }
}