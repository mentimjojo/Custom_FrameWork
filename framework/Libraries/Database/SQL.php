<?php

/**
 * Class SQL
 * @Author T.Nijborg
 * @Version 0.1
 */
class SQL extends ConnectionPool
{

    /**
     * Set connection to use
     *
     * @var string
     */
    private static $use_connection = '';

    /**
     * Set Query
     *
     * @var string
     */
    private static $query;

    /**
     * Set params
     *
     * @var array
     */
    private static $params = array();

    /**
     * Query statement
     *
     * @var
     */
    private static $statement;

    /**
     * Set connection to use
     *
     * @param string $name
     * @return static
     */
    public static function connection(string $name)
    {
        // Set connection
        self::$use_connection = $name;
        // Return self
        return new static();
    }

    /**
     * Set query
     *
     * @param string $query
     * @return static
     */
    public static function setQuery(string $query)
    {
        // Set query
        self::$query = $query;
        // Return self
        return new static();
    }

    /**
     * Set params
     *
     * @param array $params
     * @return static
     */
    public static function setParams(array $params)
    {
        // Set params
        self::$params = $params;
        // Return self
        return new static();
    }

    /**
     * Execute the query
     *
     * @return stdClass
     */
    public static function execute(): stdClass
    {
        // Check if everything is ok.
        if (!empty(self::$query)) {
            // Check if exists
            if (!isset(parent::get(self::$use_connection)->status)) {
                // Check if PDO
                if (is_a(parent::get(self::$use_connection), 'MySQL_PDO')) {
                    // Setup statement
                    self::$statement = parent::get(self::$use_connection)->query(self::$query, self::$params);
                } else {
                    // Setup statement
                    self::$statement = parent::get(self::$use_connection)->query(self::$query);
                }
                // Return stm
                $return = array('status' => true, 'message' => 'success_query_run');
                // Set connection back
                self::$use_connection = '';
            } else {
                // Return
                $return = array('status' => false, 'message' => 'error_connection_not_exists');
            }
        } else {
            // Return
            $return = array('status' => false, 'message' => 'error_query_empty');
        }
        // Return
        return (object)$return;
    }

    /**
     * Get return from query.
     *
     * @return mixed
     */
    public static function getReturn()
    {
        // Return query statement
        return self::$statement;
    }

}


?>