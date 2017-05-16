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

/**
 * Test fase
 */
ErrorHandler::debug(true);
?>