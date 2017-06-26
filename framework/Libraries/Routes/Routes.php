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
    private static $default;

    /**
     * Array with all routes
     * @var array
     */
    private static $routes = array();

    /**
     * Routes constructor.
     * Startup route controller.
     */
    public function __construct()
    {
        // Get route
        $this->getRoute();
    }

    /**
     * Get route from url, so ?route=value
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
     * Create new route
     * @param string $name
     * @param string $path
     */
    public static function create(string $name, string $path)
    {
        // Block chars
        $block_chars = array('&', '#', '$', '^', '*', '(', ')', '\\', '|', ':', ';', ',', '.', '"', "'");
        // Utils
        if (!Utils::$misc->checkStringOnChars($name, $block_chars)) {
            // Check if route is valid
            self::$routes[$name] = $path;
        } else {
            ErrorHandler::warning(106, 'Route url contains not supported chars. Chars allowed: /, -, _');
        }
    }

    /**
     * Set the default page
     * @param string $path
     */
    public static function setDefault(string $path)
    {
        // Check exists
        if (file_exists(Constants::path_public . '/' . $path)) {
            // Set default
            self::$default = $path;
        } else {
            ErrorHandler::die(100, "Home file doesn't exists.");
        }
    }

    /**
     * Load system, this mean routing is loading. This function is self used.
     */
    public static function load()
    {
        // Check set
        if (isset(self::$routes[self::$route])) {
            // Get file & path
            $file = Constants::path_public . '/' . self::$routes[self::$route];
            // Check if exists
            if (file_exists($file)) {
                require_once $file;
            } else {
                // Check default exists
                if (isset(self::$default)) {
                    // Require default
                    require_once Constants::path_public . '/' . self::$default;
                } else {
                    // Die
                    ErrorHandler::die(100, 'Default is not set, and file/route is not found.');
                }
            }
        } else {
            // Check default exists
            if (isset(self::$default)) {
                // Require default
                require_once Constants::path_public . '/' . self::$default;
            } else {
                // Die
                ErrorHandler::die(100, 'Default is not set, and file/route is not found.');
            }
        }
    }

}

?>