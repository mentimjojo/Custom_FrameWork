<?php

/**
 * Created by T.Nijborg
 * Class Database
 */
class Database extends ConnectionPool
{

    /**
     * @var string
     */
    private $name;

    /**
     * Supported Database engines
     * @var array
     */
    private $engines = array("mysqli", "pdo", "mssql", "sqlite3");

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
     * File name for SQLite3
     * @var string
     */
    private $file_name;

    /**
     * Connection
     * @var
     */
    private $db_engine;

    /**
     * Set name of the database
     * @param string $name
     */
    public function setName(string $name)
    {
        // Set name
        $this->name = $name;
    }

    /**
     * Set database type
     * @param string $engine
     * @return array
     */
    public function setEngine(string $engine): array
    {
        // Check type supported
        if (in_array(strtolower($engine), $this->engines)) {
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
    public function setMsServer(string $srvName, int $port = 1433)
    {
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
     * Set file name
     * ONLY FOR SQLITE3
     * @param string $file_name
     */
    public function setFileName(string $file_name)
    {
        /*
         * Set file name
         */
        $this->file_name = $file_name;
    }

    /**
     * Create connection
     */
    public function createConnection()
    {
        // Switch to right Engine
        switch ($this->engine) {
            case "pdo":
                $this->db_engine = new MySQL_PDO($this->credentials);
                break;
            case "mysqli":
                $this->db_engine = new MySQL_MySQLi($this->credentials);
                break;
            case "mssql":
                $this->db_engine = new MSSQL_SQLSRV($this->serverName, $this->credentials);
                break;
            case "sqlite3":
                $this->db_engine = new MySQL_SQLite3($this->file_name);
                break;
            default:
                ErrorHandler::die(101, 'Database engine not supported');
                break;
        }
        // Save database object
        self::create($this->name, $this->db_engine);
    }

    /**
     * Set this database as global for query's, etc.
     */
    public function setGlobal()
    {
        // Set this as global
        self::global($this->name);
    }

    /**
     * Get connection to create your own query system.
     * @return mixed
     */
    public function getConnection()
    {
        // Check if db engine is set
        if (isset($this->db_engine)) {
            // Return connection
            return $this->db_engine->getConnection();
        } else {
            // Return null
            return null;
        }
    }
}

?>