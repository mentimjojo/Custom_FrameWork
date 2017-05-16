<?php
/**
 * Get Core
 */
require_once __DIR__ . '/core/Core.php';

/**
 * Create new instance of core
 */
new Core();

/**
 * Test fase
 */
ErrorHandler::debug(true);


$database = new Database();
$database->setEngine('PDO');
$database->setCredentials('localhost', 'ictdev_framework', 'ictdev_framework', 'OEiim@Qb%(vJ');
$database->createConnection();

var_dump($database->getConnection());

$query = $database->runQuery('SELECT * FROM test');

foreach ($query as $item){
    var_dump($item);
    echo '<br/><br/>';
}

?>