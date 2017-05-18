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
        // Initialize framework
        $this->initialize();
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
        $Routes = new Routes();
        // Run utils
        new Utils();
        // Load public
        $autoLoader->loadPublic();
        // Load routes
        $Routes->load();
    }

}