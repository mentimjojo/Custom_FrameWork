<?php
/**
 * Default Index.php
 * The framework loads this file in. From here on you can build your complete website.
 * DON'T DELETE THIS FILE, BECAUSE YOUR SITE WILL NOT WORK WITHOUT IT.
 */

Settings::toggleSSL(true);
Settings::toggleDebug(true);

$db = new Database();
$db->setName('test');
$db->setEngine('PDO');
$db->setCredentials('localhost', 'ictdev_framework', 'ictdev_framework', '#A*?fVF?kmJw');
$db->createConnection();
$db->setGlobal();

$db = new Database();
$db->setName('test2');
$db->setEngine('PDO');
$db->setCredentials('localhost', 'ictdev_framework', 'ictdev_framework', '#A*?fVF?kmJw');
$db->createConnection();

SQL::connection('test2');
SQL::setQuery('SELECT * FROM test WHERE test_name = :name');
SQL::setParams(array(':name' => "Tim"));
var_dump(SQL::execute()->fetch());


?>