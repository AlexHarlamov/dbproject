<?php


namespace app\core\pages;


use app\core\util\App;
use app\core\util\Env;

class listelements implements Page
{

    public static function fr_prepare_data()
    {
        $arr =  App::call(DATABASE_WORKER, "select", [
            "from" => "lemma_classes",
            "what" => [
                "I_NAME"
            ],
            "filter" =>[
                "ID"
            ],
            "conditions" => [
                Env::get("classid")
            ]
        ]);
        //echo "Структура класса:<br />";
       /* foreach ($arr as $v){
            foreach ($v as $key => $value) {
                echo "| $key = $value ";
            }
        }*/
        $table = "class_".($arr[0])["I_NAME"];

        $arr =  App::call(DATABASE_WORKER, "select", [
            "from" => $table,
            "what" => [
                "*"
            ]
        ]);

        echo "Структура элементов класса:<br />";
        foreach ($arr as $v){

            foreach ($v as $key => $value) {
                echo "| $key = $value ";
            }
            $id = $v["OWNER_ELEMENT_ID"];

            $arr_inner =  App::call(DATABASE_WORKER, "select", [
                "from" => "lemma_templates",
                "what" => [
                    "ID",
                    "NAME"
                ],
                "filter" => [
                    "OWNER_CLASS_ID"
                ],
                "conditions" => [
                    Env::get("classid")
                ]
            ]);

            echo "|| Посмотреть элемент через шаблон: ";
            foreach ($arr_inner as $v_inner){

                $templateid = $v_inner["ID"];
                $templatename = $v_inner["NAME"];

                echo "<a href=\"../elementintemplate?elementid=$id&templateid=$templateid\">$templatename</a>";
                echo "  |  ";
            }

                 $arr_inner =  App::call(DATABASE_WORKER, "select", [
                "from" => "lemma_links",
                "what" => [
                    "TO_ELEMENT_ID"
                ],
                "filter" => [
                    "FROM_ELEMENT_ID"
                ],
                "conditions" => [
                    $id
                ]
            ]);
             echo "|| Исходящя связь с элементами, чьи id : ";
            foreach ($arr_inner as $v_inner){
                foreach ($v_inner as $value){
                    echo $value." , ";
                }
            }

            $arr_inner =  App::call(DATABASE_WORKER, "select", [
                "from" => "lemma_links",
                "what" => [
                    "FROM_ELEMENT_ID"
                ],
                "filter" => [
                    "TO_ELEMENT_ID"
                ],
                "conditions" => [
                    $id
                ]
            ]);
            echo "|| Входящая связь с элементами, чьи id : ";
            foreach ($arr_inner as $v_inner){
                foreach ($v_inner as $value){
                    echo $value." , ";
                }
            }



            echo "<br />";

        }
        echo "<hr>";
        echo "<a href=\"../listclasses?classid=$id\">Посмотреть все классы</a><br />";

    }

    public static function fr_parse_template()
    {
        // TODO: Implement fr_parse_template() method.
    }

    public static function fr_get_table_name(){
    }
}