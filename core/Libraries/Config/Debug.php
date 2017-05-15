<?php
class Debug {

    /**
     * Debug constructor.
     */
    public function __construct()
    {
        // Init Debug
        $this->setDebug();
    }

    /**
     * Set debug
     */
    private function setDebug(){
        if(Constants::$debug){
            // Display errors
            ini_set('display_errors', 1);
            // Enable error reporting
            error_reporting(E_ALL);
        } else {
            // Display no errors
            ini_set('display_errors', 0);
            // disable error reporting
            error_reporting(0);
        }
    }

}
?>