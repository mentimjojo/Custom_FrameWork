<?php
class AutoLoader {

    /**
     * AutoLoader constructor.
     * @param string $path the path to scan
     */
    public function __construct(string $path)
    {
        $this->internalLoad($path);
    }

    /**
     * AutoLoader
     * Include all files in Core, and scan all dirs in it.
     * @param string $path
     * @internal Important feature
     * @version 0.1
     */
    private function internalLoad(string $path){
        // Scan path
        $scan = array_diff(scandir($path), array('.', '..', 'Core.php', 'Error_Template.php'));
        // Foreach found item
        foreach ($scan as $item){
            // Check if item is a file
            if(substr($item, -4) == ".php") {
                // Require file once
                require_once $path . '/' . $item;
            } else if(substr($item, -5) == ".html"){
                // Ignore
            } else {
                // Rerun autoload on new folder
                $this->internalLoad($path . '/' . $item);
            }
        }
    }

    /**
     * AutoLoad files
     * @param string $path
     */
    public static function load(string $path){
        // Set path
        $path = Constants::path_public . '/' . $path;
        // Scan path
        $scan = array_diff(scandir($path), array('.', '..'));
        // Foreach found item
        foreach ($scan as $item){
            // Check if item is a file
            if(substr($item, -4) == ".php") {
                // Require file
                require_once $path . '/' . $item;
            } else if(substr($item, -5) == ".html"){
                // Ignore
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
    public function loadPublic(){
        // Load standard public
        require_once Constants::path_public . '/index.php';
    }

}
?>