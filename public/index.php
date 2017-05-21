<?php
/**
 * Standard index.php
 */
Settings::toggleSSL(true);
Settings::toggleDebug(true);

Routes::load();
?>