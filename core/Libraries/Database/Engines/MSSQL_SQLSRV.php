<?php

class MSSQL_SQLSRV {

    /**
     * Temp connection save
     * @var
     */
    private $connection;

    /**
     * Database credentials
     * @var
     */
    private $credentials;

    /**
     * Server name & port
     * @var
     */
    private $serverName;

    /**
     * PDO constructor.
     * @param string $serverName the server name & port
     * @param array $credentials of the database
     */
    public function __construct(string $serverName, array $credentials)
    {
        // Save credentials
        $this->credentials = (object) $credentials;
        // Connect
        $this->connect();
    }

    /**
     * Connect to database with SqlSrv connect
     */
    private function connect(){
        try {
            $this->connection = sqlsrv_connect(
                $this->serverName,
                array(
                    "Database" => $this->credentials->name,
                    "UID" => $this->credentials->user,
                    "PWD" => $this->credentials->pass
                )
            );
        } catch (Exception $ex){
            ErrorHandler::die(102, 'No database connection. Please check your credentials or is the server offline? Error: ' . $ex->getMessage());
        }
    }

    /**
     * Get connection
     * @return mixed
     */
    public function getConnection(){
        if(isset($this->connection)){
            return $this->connection;
        } else {
            return null;
        }
    }

    /**
     * Run query
     * @param string $query
     * @return array|bool
     */
    public function query(string $query) {
        // Check query not empty
        if(empty($query)){
            return array('status' => false, 'msg' => 'No query filled in');
        } else {
            try {
                // Run query
                $stm = sqlsrv_query($this->connection, $query);
                // Return query
                return $stm;
            } catch (Exception $ex){
                ErrorHandler::die(103, 'Query: ' . $query . ' has failed: ' . $ex->getMessage());
            }
        }
        // Just to be sure
        return false;
    }

}
?>