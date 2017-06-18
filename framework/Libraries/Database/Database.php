<?php

/**
 * Class Database
 * @Author T.Nijborg
 * @Version 0.3
 */
class Database extends ConnectionPool
{

    /**
     * Name of the connection
     *
     * @var string
     */
    private $name;

    /**
     * Supported Database engines
     *
     * @var array
     */
    private $engines = array("mysqli", "pdo", "mssql", "sqlite3");

    /**
     * Database engine to connect
     *
     * @var
     */
    private $engine;

    /**
     * Server name & Port
     *
     * @var
     */
    private $serverName;

    /**
     * Credentials for the Database
     *
     * @var array
     */
    private $credentials = array();

    /**
     * File name for SQLite3
     *
     * @var string
     */
    private $file_name;

    /**
     * Connection
     *
     * @var
     */
    private $db_engine;

    /**
     * Set the name of the connection
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        // Set name
        $this->name = $name;
        // Return this
        return $this;
    }

    /**
     * Set engine of the connection
     *
     * @param string $engine
     * @return $this
     */
    public function setEngine(string $engine)
    {
        // Check type supported
        if (in_array(strtolower($engine), $this->engines)) {
            // Set type
            $this->engine = strtolower($engine);
        } else {
            // Return error
            ErrorHandler::die(101, 'Database engine not supported');
        }
        // Return this object
        return $this;
    }

    /**
     * Set server name & port for MSSQL
     *
     * @param string $srvName
     * @param int $port
     * @return $this
     */
    public function setMsServer(string $srvName, int $port = 1433)
    {
        // Save in variable
        $this->serverName = $srvName . ', ' . $port;
        // Return this object
        return $this;
    }

    /**
     * Setup credentials Database
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $db_name
     * @param int $port
     * @return $this
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
        // Return this object
        return $this;
    }

    /**
     * Set file name
     * ONLY FOR SQLITE3
     * @param string $file_name
     * @return $this
     */
    public function setFileName(string $file_name)
    {
        /*
         * Set file name
         */
        $this->file_name = $file_name;
        // Return this object
        return $this;
    }

    /**
     * Create connection
     *
     * @return $this
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
        parent::create($this->name, $this->db_engine);
        // Return this
        return $this;
    }

    /**
     * Set connection as global, only available when connection created
     *
     * @return $this
     */
    public function setGlobal()
    {
        // Set this as global
        parent::global($this->name);
        // Return this object
        return $this;
    }

    /**
     * Get connection to create your own query system.
     *
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