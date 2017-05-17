<?php

class Routes
{

    /**
     * Route
     * @var
     */
    private static $route;

    /**
     * Set home
     * @var
     */
    private static $home;

    /**
     * Array with all routes
     * @var array
     */
    private static $routes = array();

    /**
     * Get route
     * Routes constructor.
     */
    public function __construct()
    {
        // Get route
        $this->getRoute();
    }

    /**
     * Get route from url
     */
    private function getRoute()
    {
        // Check if route isset
        if (isset($_GET['route'])) {
            // Get route from URL
            self::$route = $_GET['route'];
        }
    }

    /**
     * create route
     * @param string $url
     * @param string $path
     */
    public static function create(string $url, string $path)
    {
        // Block chars
        $block_chars = array('&', '#', '$', '^', '*', '(', ')', '\\', '|', ':', ';', ',', '.', '"', "'");
        // Utils
        if(!Utils::$misc->checkStringOnChars($url, $block_chars)) {
            // Check if route is valid
            self::$routes[$url] = $path;
        } else {
            ErrorHandler::warning(106, 'Route url contains not supported chars. Chars allowed: /, -, _');
        }
    }

    /**
     * Set home
     * @param string $path
     */
    public static function setHome(string $path)
    {
        // Check exists
        if (file_exists(Constants::path_public . '/' . $path)) {
            // Set home
            self::$home = $path;
        } else {
            ErrorHandler::die(100, "Home file doesn't exists.");
        }
    }

    /**
     * Load system
     */
    public function load()
    {
        // Check set
        if (isset(self::$routes[self::$route])) {
            // Get file & path
            $file = Constants::path_public . '/' . self::$routes[self::$route];
            // Check if exists
            if (file_exists($file)) {
                require_once $file;
            } else {
                // Check home exists
                if (isset(self::$home)) {
                    // Require home
                    require_once Constants::path_public . '/' . self::$home;
                } else {
                    // Die
                    ErrorHandler::die(100, 'Home is not set, and file/route is not found.');
                }
            }
        } else {
            // Check home exists
            if (isset(self::$home)) {
                // Require home
                require_once Constants::path_public . '/' . self::$home;
            } else {
                // Die
                ErrorHandler::die(100, 'Home is not set, and file/route is not found.');
            }
        }
    }

}

?>