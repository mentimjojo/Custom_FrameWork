<?php

class Settings
{

    /**
     * Toggle debug
     * @param bool $toggle
     */
    public static function toggleDebug(bool $toggle)
    {
        // Update Debug
        Constants::$debug = $toggle;
    }

    /**
     * Toggle SSL, when enabled system will force SSL
     * @param bool $toggle
     */
    public static function toggleSSL(bool $toggle)
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

    /**
     * Check ssl, when enabled, check url if ssl is being used.
     */
    private static function checkSSL()
    {
        // Check if enabled
        if (Constants::$ssl) {
            if (!isset($_SERVER["HTTPS"])) {
                Utils::$header->redirect(Constants::$url_root . $_SERVER["REQUEST_URI"], 0, true);
            }
        }
    }

}

?>