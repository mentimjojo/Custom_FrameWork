<?php
/**
 * Standard index.php
 */

Routes::setHome('home.php');
Routes::create('home', 'home.php');
Routes::create('upload', 'upload.php');
Routes::create('download', 'download.php');
?>