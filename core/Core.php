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
        new AutoLoader(__DIR__);
    }

}