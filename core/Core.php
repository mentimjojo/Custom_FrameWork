<?php

/**
 * Get AutoLoader
 */
require_once __DIR__ . '/Libraries/AutoLoader/AutoLoader.php';

/**
 * Created by T.Nijborg
 * Class Core
 */
class Core
{

    /**
     * Core constructor.
     */
    public function __construct()
    {
        // Run AutoLoader
        $autoLoader = new AutoLoader(__DIR__);
        // Run config
        new Config();
        // Run Error
        new ErrorHandler();
        // Run routes
        new Routes();
        // Load public
        $autoLoader->loadPublic();
        // Load routes
        Routes::load();
    }

}