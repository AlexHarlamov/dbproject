<?php


namespace app\core\application\database;


use app\core\application\Application;
use app\core\util\Env;
use PDO;
use PDOException;
use PDOStatement;

class FR_DatabaseWorker implements Application
{
    private PDO $db;
    private string $request;
    private array $result;
    private ?array $conditions = NULL;
    private PDOStatement $state;

    public function __construct()
    {
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->db = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, $opt);
    }

    public function select(array $params)
    {

        if(!empty($params["what"]))
            $what = $params["what"];

        if(empty($params["from"])){
            throw new PDOException(" FROM property is not defined ");
        }else{
            $from = $params["from"];
        }

        if(!empty($params["filter"])){
            $filter_fields = $params["filter"];

            if(!empty($params["conditions"])){
                $conditions = $params["conditions"];
                $this->conditions = $this->wrapMassViaNames($filter_fields, $conditions);
            }else{
                throw new PDOException(" FILTER fields added but parameters are not ");
            }
        }

        if(!empty($params["limit"]))
            $limit = $params["limit"];

        $this->request = "";

        $this->request = $this->request . "select ";

        if(empty($what)){
            $this->request = $this->request . " * ";
        }else{
            foreach ($what as $value) {
                $this->request = $this->request . $value . ",";
            }
        }

        $this->request = substr($this->request, 0, -1);
        $this->request = $this->request . " from " . $from;

        if (!empty($filter_fields)) {
            $this->request = $this->request . " where ";
            foreach ($filter_fields as $field) {
                $this->request = $this->request . $field . "= :" . $field . " and ";
            }
            $this->request = substr($this->request, 0, -5);
        }

        if (isset($limit))
            $this->request = $this->request . " limit " . $limit;

        if (isset($offset))
            $this->request = $this->request . " offset " . $offset;

        try {
            $this->state = $this->db->prepare($this->request);
            $this->state->execute($this->conditions);
            $this->result = $this->state->fetchAll();
        } catch (PDOException $exception) {
            throw $exception;
        }

        Env::set('LastSelect',$this->result);

        return $this->result;
    }

    private function wrapMassViaNames($names,$values){
        if(empty($names) || empty($values)) return NULL;

        $new_mass = array();

        $tmp_counter1 = 0;
        $tmp_counter2 = 0;
        foreach ($names as $name){
            foreach ($values as $value){

                if ($tmp_counter1 == $tmp_counter2){
                    $new_mass[$name] = $value;
                }
                $tmp_counter2++;
            }
            $tmp_counter1++;
            $tmp_counter2 = 0;
        }

        return $new_mass;
    }

}
