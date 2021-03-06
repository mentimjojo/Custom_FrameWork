<?php

class Settings
{

    /**
     * Settings
     *
     * @var array settings in array
     */
    private static $settings = array();

    /**
     * Toggle debug
     * @param bool $toggle
     */
    public static function toggleDebug(bool $toggle)
    {
        // Update Debug
        Constants::$debug = $toggle;
        // Rerun debug
        new Debug();
    }

    /**
     * Toggle SSL, when enabled system will force SSL
     * @param bool $toggle
     */
    public static function toggleSSL(bool $toggle)
    {
        // Update SSL
        Constants::$ssl = $toggle;
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
     * Set settings variable
     *
     * @param string $key
     * @param $value
     */
    public static function set(string $key, $value)
    {
        // Add to array
        self::$settings[$key] = $value;
    }

    /**
     * Get settings variable
     *
     * @param string $key
     * @return mixed|null
     */
    public static function get(string $key)
    {
        if (isset(self::$settings[$key])) {
            return self::$settings[$key];
        } else {
            return null;
        }
    }

    /**
     * Check ssl, when enabled, check url if ssl is being used.
     */
    private static function checkSSL()
    {
        // Check if enabled
        if (Constants::$ssl) {
            // Update SSL in root url
            Constants::$url_root = str_replace('http://', 'https://', Constants::$url_root);
            // Update SSL in public url
            Constants::$url_public = str_replace('http://', 'https://', Constants::$url_public);
            // Redirect if needed
            if (!isset($_SERVER["HTTPS"])) {
                Utils::$header->redirect(Constants::$url_root . $_SERVER["REQUEST_URI"], 0, true);
            }
        } else {
            // Remove SSL in root url
            Constants::$url_root = str_replace('https://', 'http://', Constants::$url_root);
            // Remove SSL in public url
            Constants::$url_public = str_replace('https://', 'http://', Constants::$url_public);
            // Redirect if needed
            if (isset($_SERVER["HTTPS"])) {
                Utils::$header->redirect(Constants::$url_root . $_SERVER["REQUEST_URI"], 0, true);
            }
        }
    }

}

?>