<?php

/**
 * Class ConnectionPool
 * @Author T.Nijborg
 * @Version 0.1
 */
class ConnectionPool
{

    /**
     * All the connections
     * @var array
     */
    private static $connections = array();

    /**
     * Global connection
     * @var string
     */
    private static $global;

    /**
     * Create connection
     * @param string $name
     * @param $connection
     */
    final protected static function create(string $name, $connection)
    {
        // Save connection
        self::$connections[$name] = $connection;
    }

    /**
     * Set as global
     * @param string $name
     * @return stdClass
     */
    final protected static function global(string $name): stdClass
    {
        // Check isset
        if (isset(self::$connections[$name])) {
            // Set name as global
            self::$global = $name;
            // Set return
            $return = array('status' => true, 'message' => 'success');
        } else {
            // Return
            $return = array('status' => false, 'message' => 'error_connection_not_exists');
        }
        // Return as object
        return (object)$return;
    }

    /**
     * Return connection
     * @param string $name
     * @return stdClass
     */
    final protected static function get(string $name = '')
    {
        // Check empty
        if(empty($name)){
            // Set global to use
            $name = self::$global;
        }
        // Check if isset
        if (isset(self::$connections[$name])) {
            // Set return
            $return = self::$connections[$name];
        } else {
            // Return
            $return = array('status' => false, 'message' => 'error_connection_not_exists');
        }
        // Return as object
        return (object)$return;
    }

}

?>