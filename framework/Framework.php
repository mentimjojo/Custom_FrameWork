<?php

/**
 * Get AutoLoader
 */
require_once __DIR__ . '/Core/AutoLoader/AutoLoader.php';

/**
 * Core class of the framework
 * This class loads the complete framework and public folder (index.php). It loads all the essential features.
 * DO NOT EDIT THIS CLASS.
 * @Author T.Nijborg
 * @Version 0.1.1
 * @Since 0.2
 *
 * Class Core
 */
class Framework
{
    /**
     * Instants of the AutoLoader
     * @var AutoLoader
     */
    private $AutoLoader;

    /**
     * Core constructor.
     */
    public function __construct()
    {
        // Startup
        $this->_Startup();
        // Initialize framework
        $this->_Initialize_FrameWork();
        // Load public (APP)
        $this->_Load_Public();
        // After load
        $this->_After();
    }

    /**
     * Startup some needed PHP features
     */
    private function _Startup()
    {
        // Startup checks
        $this->_Startup_Checks();
        // Start ob
        ob_start();
        // Start sessions
        session_start();
    }

    /**
     * Startup checks
     */
    private function _Startup_Checks()
    {
        // Check if php version is higher then 7.1
        if(phpversion() < 7.1){
            // Die the site
            die('PHP Version is not supported. You are running ' . phpversion() . ', while the framework needs at least 7.1');
        }
    }

    /**
     * Initialize framework
     * - Loads in this folder
     */
    private function _Initialize_FrameWork()
    {
        // Setup AutoLoader in this folder
        $this->AutoLoader = new AutoLoader(__DIR__);
        // Initialize config
        new Config();
        // Initialize error handler
        new ErrorHandler();
        // Initialize routes
        new Routes();
        // Initialize routes
        new Utils();
    }

    /**
     * Load public folder
     */
    private function _Load_Public()
    {
        // Load public folder
        $this->AutoLoader->loadPublic();
    }

    /**
     * After loading is done
     */
    private function _After()
    {

    }

}