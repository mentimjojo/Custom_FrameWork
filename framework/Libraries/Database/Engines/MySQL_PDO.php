<?php

class MySQL_PDO
{

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
     *
     * @param array $credentials of the database
     */
    public function __construct(array $credentials)
    {
        // Save credentials
        $this->credentials = (object)$credentials;
        // Connect
        $this->connect();
    }

    /**
     * Connect to database with PDO
     */
    private function connect()
    {
        // Try
        try {
            // Setup database connection
            $this->connection = new PDO(
                'mysql:host=' . $this->credentials->host . ';port=' . $this->credentials->port . ';dbname=' . $this->credentials->name . ';',
                $this->credentials->user,
                $this->credentials->pass
            );
        } catch (Exception $ex) {
            // Return warning
            ErrorHandler::die(102, 'No database connection. Please check your credentials or is the server offline? Error: ' . $ex->getMessage());
        }
    }

    /**
     * Get connection
     *
     * @return mixed
     */
    public function getConnection()
    {
        // Check connection is set
        if (isset($this->connection)) {
            // Return connection
            return $this->connection;
        } else {
            // Return null
            return null;
        }
    }

    /**
     * Run query
     *
     * You can add params
     *
     * @param string $query
     * @param array $params
     * @return array|bool
     */
    public function query(string $query, array $params = array())
    {
        // Check query not empty
        if (empty($query)) {
            // Return
            return array('status' => false, 'msg' => 'No query filled in');
        } else {
            try {
                // Prepare query
                $stm = $this->connection->prepare($query);
                // Execute query
                $stm->execute($params);
                // Return query
                return $stm;
                // Catch
            } catch (Exception $ex) {
                // Return warning
                ErrorHandler::warning(103, 'Query: ' . $query . ' has failed: ' . $ex->getMessage());
            }
        }
        // Just to be sure
        return false;
    }

    /**
     * Get last inserted id
     *
     * @return mixed
     */
    public function getLastInsertedID(){
        // Return last created id
        return $this->connection->lastInsertId();
    }

}

?>