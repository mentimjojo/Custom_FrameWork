<?php
// Display errors
ini_set('display_errors', 1);
// Enable error reporting
error_reporting(E_ALL);
/**
 * Get Core
 */
require_once __DIR__ . '/core/Core.php';

/**
 * Create new instance of core
 */
new Core();

ErrorHandler::debug(true);

$database = new Database();
$database->setType('MySQLi');
$database->setCredentials('localhost', 'ictdev_framework', 'ictdev_framework', 'OEiim@Qb%(vJ');
$database->createConnection();

$query = $database->runQuery('SELECT * FROM test');

foreach ($query as $item){
    var_dump($item);
    echo '<br/><br/>';
}

trigger_error("leuk");
?>