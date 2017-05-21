<?php

/**
 * Get AutoLoader
 */
require_once __DIR__ . '/Libraries/AutoLoader/AutoLoader.php';

/**
 * Core class of the framework
 * @Author T.Nijborg
 * @Version 0.1.1
 * @Since 0.1
 * Class Core
 */
class Core
{

    /**
     * Core constructor.
     */
    public function __construct()
    {
        // Startup
        $this->startup();
        // Initialize framework
        $this->initialize();
    }

    /**
     * Startup some needed PHP features
     */
    private function startup(){
        // Start ob
        ob_start();
        // Start sessions
        session_start();
    }

    /**
     * Initialize framework
     */
    private function initialize(){
        // Run AutoLoader
        $autoLoader = new AutoLoader(__DIR__);
        // Run config
        new Config();
        // Run Error
        new ErrorHandler();
        // Run routes
        new Routes();
        // Run utils
        new Utils();
        // Load public
        $autoLoader->loadPublic();
    }

}