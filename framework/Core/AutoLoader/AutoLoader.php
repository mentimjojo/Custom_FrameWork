<?php

class AutoLoader
{

    /**
     * AutoLoader constructor.
     * @param string $path the path to scan
     */
    public function __construct(string $path)
    {
        // Load framework internal
        $this->internalLoad($path);
    }

    /**
     * AutoLoader
     * Include all files in Core, and scan all dirs in it.
     * @param string $path
     * @internal Important feature
     * @version 0.1
     */
    public function internalLoad(string $path)
    {
        // Scan path
        $scan = array_diff(scandir($path), array('.', '..', 'Core.php', 'Resources'));
        // Foreach found item
        foreach ($scan as $item) {
            // Check if item is a file
            if (substr($item, -4) == ".php") {
                // Require file once
                require_once $path . '/' . $item;
            } else {
                // Rerun autoload on new folder
                $this->internalLoad($path . '/' . $item);
            }
        }
    }

    /**
     * AutoLoad files (Only PHP)
     * @param string $path
     * @param array $filter set the filter
     */
    public static function load(string $path, array $filter = array())
    {
        // Setup filters, should fix dots in scan dir.
        $filter[] = '.';
        $filter[] = '..';
        // Remove constants path
        $path = str_replace(Constants::path_public . '/', '', $path);
        // Set path
        $path = Constants::path_public . '/' . $path;
        // Scan path
        $scan = array_diff(scandir($path), $filter);
        // Foreach found item
        foreach ($scan as $item) {
            // Check if item is a file
            if (substr($item, -4) == ".php") {
                // Require file
                require_once $path . '/' . $item;
            } else {
                // Rerun autoload on new folder
                self::load($path . '/' . $item);
            }
        }
    }

    /**
     * Load public index.php
     * Always load last
     */
    public function loadPublic()
    {
        // Disable debug, because startup almost complete
        Settings::toggleDebug(false);
        // Load standard public, to be safe require once
        require_once Constants::path_public . '/index.php';
        // Set startup complete, so debug can't enable itself again.
        Constants::$startup_complete = true;
    }

}

?>