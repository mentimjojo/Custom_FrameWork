<?php

class MySQL_MySQLi {

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
     * PDO constructor.
     * @param array $credentials of the database
     */
    public function __construct(array $credentials)
    {
        // Save credentials
        $this->credentials = (object) $credentials;
        // Connect
        $this->connect();
    }

    /**
     * Connect to database with MySQLi
     */
    private function connect(){
        try {
            $this->connection = new mysqli(
                $this->credentials->host,
                $this->credentials->user,
                $this->credentials->pass,
                $this->credentials->name,
                $this->credentials->port
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
                // Prepare query
                $stm = $this->connection->query($query);
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