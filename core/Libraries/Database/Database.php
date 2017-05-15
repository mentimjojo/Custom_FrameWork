<?php

/**
 * Created by T.Nijborg
 * Class Database
 */
class Database
{

    /**
     * Supported Database engines
     * @var array
     */
    private $types = array("MySQLi", "PDO");

    /**
     * Database type to connect
     * @var
     */
    private $type;

    /**
     * Credentials for the Database
     * @var array
     */
    private $credentials = array();

    /**
     * Connection
     * @var
     */
    private $db_engine;

    /**
     * Set database type
     * @param string $Type
     * @return array
     */
    public function setType(string $Type) : array {
        // Check type supported
        if(in_array($Type, $this->types)){
            // Set type
            $this->type = $Type;
            // Return true
            return array('true');
        } else {
            // Return error
            ErrorHandler::die(101, 'Database engine not supported');
        }
        // Just to be sture
        return array('false');
    }

    /**
     * Setup credentials Database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $db_name
     * @param int $port
     */
    public function setCredentials(string $host, string $db_name, string $user, string $pass, int $port = 3600)
    {
        /**
         * Set credentials
         */
        $this->credentials = array(
            "host" => $host,
            "user" => $user,
            "pass" => $pass,
            "name" => $db_name,
            "port" => $port
        );
    }

    /**
     * Create connection
     */
    public function createConnection(){
        // Switch to right Engine
        switch ($this->type){
            case "PDO":
                $this->db_engine = new MySQL_PDO($this->credentials);
                break;
            case "MySQLi":
                $this->db_engine = new MySQL_MySQLi($this->credentials);
                break;
        }
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function runQuery(string $query){
        switch ($this->type){
            case "PDO":
                $stm = $this->db_engine->query($query);
                break;
            case "MySQLi":
                $stm = $this->db_engine->query($query);
                break;
            default:
                $stm = array('status' => false, 'msg' => 'Error. Did you select a database type?!');
                break;
        }
        // Return query
        return $stm;
    }


}

?>