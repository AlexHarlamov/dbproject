<?php


namespace controller\database;

use PDO;
use PDOException;

class DBSelector
{

private $db;
    private $request = null;
    private $result = null;
    private $conditions;

    public function __construct()
    {
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $this->db = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, $opt);
    }

    public function sendRequest($className)
    {
        $this->request = "SELECT * FROM axiom_ATTRIBUTES INNER JOIN axiom_CLASSES on axiom_ATTRIBUTES.OWNER_CLASS_ID = axiom_CLASSES.ID WHERE axiom_CLASSES.NAME LIKE $className";
        try {
            $state = $this->db->prepare($this->request);
            $state->execute($this->conditions);
            $this->result = $state->fetchAll();
        } catch (PDOException $exception) {
        }
        return $this;
    }

    public function getAnswer()
    {
        return $this->result;
    }

    public function toString()
    {
        return $this->request;
    }

    public function setSpecialRequest($request){
        $this->request = $request;
        return $this;
    }

}
