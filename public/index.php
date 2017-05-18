<?php
/**
 * Standard index.php
 */

Routes::setHome('home.php');
Routes::create('home', 'home.php');

trigger_error('hi');
?>