<?php
/**
 * Standard index.php
 */

Routes::setHome('home.php');
Routes::create('home', 'home.php');
echo Constants::path_public . '<br/>';
?>