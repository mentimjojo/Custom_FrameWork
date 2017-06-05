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
    private $engines = array("mysqli", "pdo", "mssql");

    /**
     * Database engine to connect
     * @var
     */
    private $engine;

    /**
     * Server name & Port
     * @var
     */
    private $serverName;

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
     * @param string $engine
     * @return array
     */
    public function setEngine(string $engine) : array {
        // Check type supported
        if(in_array(strtolower($engine), $this->engines)){
            // Set type
            $this->engine = strtolower($engine);
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
     * Set server name & port for MSSQL
     * @param string $srvName
     * @param int $port
     */
    public function setMsServer(string $srvName, int $port = 1433){
        // Save in variable
        $this->serverName = $srvName . ', ' . $port;
    }

    /**
     * Setup credentials Database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $db_name
     * @param int $port
     */
    public function setCredentials(string $host, string $db_name, string $user, string $pass, int $port = 3306)
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
        switch ($this->engine){
            case "pdo":
                $this->db_engine = new MySQL_PDO($this->credentials);
                break;
            case "mysqli":
                $this->db_engine = new MySQL_MySQLi($this->credentials);
                break;
            case "mssql":
                $this->db_engine = new MSSQL_SQLSRV($this->serverName, $this->credentials);
                break;
            default:
                ErrorHandler::die(101,  'Database engine not supported');
                break;
        }
    }

    /**
     * Get connection to create your own query system.
     * @return mixed
     */
    public function getConnection(){
        if(isset($this->db_engine)) {
            return $this->db_engine->getConnection();
        } else {
            return null;
        }
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function query(string $query){
        // Check if db engine exists
        if(isset($this->db_engine)) {
            // Create query
            $stm = $this->db_engine->query($query);
            // Return query
            return $stm;
        } else {
            // Return error
            return null;
        }
    }


}

?>