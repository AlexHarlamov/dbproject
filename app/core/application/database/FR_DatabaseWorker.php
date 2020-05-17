<?php


namespace app\core\application\database;


use app\core\application\Application;
use app\core\util\Env;
use PDO;
use PDOException;
use PDOStatement;


/**
 * Class FR_DatabaseWorker
 * @package app\core\application\database
 *
 * Default request configurator and executor, provides access to th database
 */
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

    public function createTable(array $params){

        $this->conditions = NULL;

        if(!empty($params["table_name"])){
            $table_name = $params["table_name"];
        }else{
            throw new PDOException("CREATE TABLE table_name property is not defined");
        }
        if(!empty($params["fields"])){
            $fields = $params["fields"];
            if(!empty($params["types"])){
                $types = $params["types"];
                $field_data_set = $this->wrapMassViaNames($fields,$types);
            }
        }

        $this->request = "";

        $this->request = $this->request . "create table " . $table_name ;

        $fields_row = "";
        if(!empty($field_data_set)){
            foreach ($field_data_set as $field => $type){
                $fields_row = $fields_row . "$field $type ,";
            }
            $fields_row = substr($fields_row, 0, -1);
        }

        $this->request = $this->request . "($fields_row)";

        try {
            $result = $this->db->query($this->request);
            Env::set('LastRequest', $this->request);
            Env::set('LastSelect',$this->result);

            return $result;
        } catch (PDOException $exception) {
            throw $exception;
        }
    }

    /**
     * @param array $params
     * @return array
     * INSERT type request constructor
     *
     * INSERT INTO "into" (field_name0, ...) VALUES (value0, ...)
     *
     */
    public function insert(array $params)
    {

        $this->conditions = NULL;

        if(!empty($params["into"])){
            $into = $params["into"];
        }else{
            throw new PDOException("INTO table_name property is not defined");
        }
        if(!empty($params["fields"])){
            $fields = $params["fields"];
            if(!empty($params["conditions"])){
                $conditions = $params["conditions"];
                $this->conditions = $this->wrapMassViaNames($fields, $conditions);
            }
        }

        $this->request = "";

        $this->request = $this->request . "insert into " . $into . " (";

        if(!empty($fields)){
            foreach ($fields as $names) {
                $this->request = $this->request . $names . ",";
            }
        }

        $this->request = substr($this->request, 0, -1) . ") values (";

        $condForExecute = null;

        if(!empty($fields) && !empty($conditions)){
            foreach ($fields as $names) {
                $this->request = $this->request . ":" . $names . ",";
                $condForExecute[':'.$names] = $conditions[$names];
            }
        }

        $this->request = substr($this->request, 0, -1) . ")";

        try {
            $this->state = $this->db->prepare($this->request);
            $this->state->execute($condForExecute);
            $id = $this->db->lastInsertId();
            $this->result = $this->state->fetchAll();
        } catch (PDOException $exception) {
            throw $exception;
        }

        Env::set('LastRequest', $this->request);
        Env::set('LastSelect',$this->result);



        return [
            "result"=> $this->result,
            "id" => $id
        ];
    }

    /**
     * @param array $params
     * @return array
     *
     * SELECT type request constructor
     *
     * request template :
     *
     * SELECT "what" ("*"-default) FROM "from"
     * (isset("filter") ? WHERE "filter_field0" = "condition0" AND ..." : none )
     * (isset("limit") ? LIMIT "limit" : none )
     * (isset("offset") ? OFFSET "offset" : none )
     *
     */
    public function select(array $params)
    {
        $this->conditions = NULL;

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

        if(!empty($params["offset"]))
            $offset = $params["offset"];

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

        Env::set('LastRequest', $this->request);
        Env::set('LastSelect',$this->result);

        return $this->result;
    }

    /**
     * @param $names
     * @param $values
     * @return array|null
     *
     * matches name value
     */
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

    public function describeTable($tableToDescribe){

        try{
            $statement = $this->db->query('DESCRIBE ' . $tableToDescribe);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
        throw $exception;
        }

        Env::set("TableDescribe",$result);
        return $result;
    }

}
