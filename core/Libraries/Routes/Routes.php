<?php
class Routes {

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
    private function getRoute(){
        // Get route from URL
        self::$route = $_GET['route'];
    }

    /**
     * Set route
     * @param string $url
     * @param string $path
     */
    public static function setRoute(string $url, string $path){
        // Save route in array
        self::$routes[$url] = $path;
    }

    /**
     * Set home
     * @param string $path
     */
    public static function setHome(string $path) {
        // Check exists
        if(file_exists(Constants::$path_public . '/' . $path)) {
            // Set home
            self::$home = $path;
        } else {
            ErrorHandler::die(100, "Home file doesn't exists.");
        }
    }

    /**
     * Load system
     */
    public static function load(){
        // Check set
        if(isset(self::$routes[self::$route])) {
            // Get file & path
            $file = Constants::$path_public . '/' . self::$routes[self::$route];
            // Check if exists
            if (file_exists($file)) {
                require_once $file;
            } else {
                // Check home exists
                if(isset(self::$home)) {
                    // Require home
                    require_once Constants::$path_public . '/' . self::$home;
                } else {
                    // Die
                    ErrorHandler::die(100, 'Home is not set, and file/route is not found.');
                }
            }
        } else {
            // Check home exists
            if(isset(self::$home)) {
                // Require home
                require_once Constants::$path_public . '/' . self::$home;
            } else {
                // Die
                ErrorHandler::die(100, 'Home is not set, and file/route is not found.');
            }
        }
    }

}
?>