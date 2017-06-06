<?php

class MySQL_SQLite3
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
    private $file_name;

    /**
     * PDO constructor.
     * @param string $file_name of the database
     */
    public function __construct(string $file_name)
    {
        // Save credentials
        $this->file_name = $file_name;
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
            // Create SQLite3
            $this->connection = new SQLite3(Constants::path_storage.'/Database/' . $this->file_name . '.db');
        } catch (Exception $ex) {
            // Return warning
            ErrorHandler::die(102, 'No database connection. Does to file exists? Error: ' . $ex->getMessage());
        }
    }

    /**
     * Get connection
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
     * @param string $query
     * @return array|bool
     */
    public function query(string $query)
    {
        // Check query not empty
        if (empty($query)) {
            // Return
            return array('status' => false, 'msg' => 'No query filled in');
        } else {
            try {
                // Prepare query
                $stm = $this->connection->query($query);
                // Return query
                return $stm;
            } catch (Exception $ex) {
                // Return warning
                ErrorHandler::warning(103, 'Query: ' . $query . ' has failed: ' . $ex->getMessage());
            }
        }
        // Just to be sure
        return false;
    }

}

?>