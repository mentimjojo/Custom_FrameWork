<?php

class MySQL_PDO {

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
     * Connect to database with PDO
     */
    private function connect(){
        try {
            $this->connection = new PDO(
                'mysql:host=' . $this->credentials->host . ';port='.$this->credentials->port.';dbname='.$this->credentials->name.';',
                $this->credentials->user,
                $this->credentials->pass
            );
        } catch (Exception $ex){
            ErrorHandler::warning(102, 'No database connection. Please check your credentials or is the server offline? Error: ' . $ex->getMessage());
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
                $stm = $this->connection->prepare($query);
                // Execute query
                $stm->execute();
                // Return query
                return $stm;
            } catch (Exception $ex){
                ErrorHandler::warning(103, 'Query: ' . $query . ' has failed: ' . $ex->getMessage());
            }
        }
        // Just to be sure
        return false;
    }

}
?>