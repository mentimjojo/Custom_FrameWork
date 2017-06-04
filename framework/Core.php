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
class Core
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
    private function _Startup(){
        // Start ob
        ob_start();
        // Start sessions
        session_start();
    }

    /**
     * Initialize framework
     * - Loads in this folder
     */
    private function _Initialize_FrameWork(){
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
    private function _Load_Public(){
        // Load public folder
        $this->AutoLoader->loadPublic();
    }

    /**
     * After loading is done
     */
    private function _After(){

    }

}