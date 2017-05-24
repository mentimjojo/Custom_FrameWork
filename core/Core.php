<?php

/**
 * Get AutoLoader
 */
require_once __DIR__ . '/Libraries/AutoLoader/AutoLoader.php';

/**
 * Core class of the framework
 * This class loads the complete framework and public folder (index.php). It loads all the essential features.
 * DO NOT EDIT THIS CLASS.
 * @Author T.Nijborg
 * @Version 0.1.1
 * @Since 0.1
 *
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
     * - Loads in this folder
     */
    private function initialize(){
        // Setup AutoLoader in this folder
        $autoLoader = new AutoLoader(__DIR__);
        // Setup config
        new Config();
        // Setup Error
        new ErrorHandler();
        // Setup routes
        new Routes();
        // Setup utils
        new Utils();
        // Load public
        $autoLoader->loadPublic();
    }

}