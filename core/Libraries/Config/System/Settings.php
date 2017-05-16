<?php
class Settings{

    /**
     * Set debug
     * @param bool $toggle
     */
    public static function setDebug(bool $toggle){
        // Update Debug
        Constants::$debug = $toggle;
    }

    /**
     * Set SSL
     * @param bool $toggle
     */
    public static function setSSL(bool $toggle){
        // Update SSL
        Constants::$ssl = $toggle;
        // Update SSL in url
        Constants::$url_root = str_replace('http://', 'https://', Constants::$url_root);
    }

}
?>