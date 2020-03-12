<?php


namespace controller\routing;

use controller\database\DBSelector;
use PDO;
use PDOException;

class Router
{
    public static function URLActionEntryPoint(){
        switch ($_GET["action"]){
            case "show_info":
                if(isset($_GET["class_name"])){
                    $opt = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ];
                    $db = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, $opt);
                    $name = $_GET["class_name"];
                    $request = "SELECT * FROM axiom_ATTRIBUTES INNER JOIN axiom_CLASSES on axiom_ATTRIBUTES.OWNER_CLASS_ID = axiom_CLASSES.ID WHERE axiom_CLASSES.NAME LIKE \"$name\" ";
                    try {
                        $state = $db->prepare($request);
                        $state->execute();
                        $result = $state->fetchAll();
                        foreach ($result as $res){
                            echo implode(",",$res);
                        }
                    } catch (PDOException $exception) {
                        echo $exception;
                    }

                }else{
                    echo "ERROR: Option of the class name not found";
                }
                break;
            default:
                echo "ERROR: Action not found";
        }
    }
}
