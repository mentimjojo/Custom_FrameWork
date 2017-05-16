<?php

class Settings
{

    /**
     * Set debug
     * @param bool $toggle
     */
    public static function setDebug(bool $toggle)
    {
        // Update Debug
        Constants::$debug = $toggle;
    }

    /**
     * Set SSL
     * @param bool $toggle
     */
    public static function setSSL(bool $toggle)
    {
        // Update SSL
        Constants::$ssl = $toggle;
        // Update SSL in url
        Constants::$url_root = str_replace('http://', 'https://', Constants::$url_root);
        // Check ssl
        self::checkSSL();
    }

    /**
     * Set string timezone
     * @param string $timeZone
     */
    public static function setTimezone(string $timeZone = "Europe/Amsterdam")
    {
        // Set timezone
        date_default_timezone_set($timeZone);
    }

    private static function checkSSL()
    {
        // Check if enabled
        if (Constants::$ssl) {
            if ($_SERVER["HTTPS"] != "on") {
                Utils::redirect(Constants::$url_root . '/' . $_SERVER["REQUEST_URI"], 0, true);
            }
        }
    }

}

?>