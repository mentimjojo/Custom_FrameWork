<?php

class Constants
{

    /**
     * Startup complete
     * Only edit this if you know the fuck what you are doing.
     * @var bool
     */
    public static $startup_complete = false;

    /**
     * Debug for the whole project
     * You can't enable it here. DEBUG Will not work.
     * @var bool
     */
    public static $debug = false;

    /**
     * Framework Version
     * DO NOT EDIT THIS. DANGER DANGER DANGER. ONLY A IDIOT WOULD EDIT THIS.
     * @var string
     */
    const fw_version = "0.1.4.001";

    /**
     * SSL disabled by default
     * You can't enable it here! SSL WON't WORK
     * @var bool
     */
    public static $ssl = false;

    /**
     * Root path
     * Without /
     * @var string
     */
    const path_root = __DIR__ . '/../../../..';

    /**
     * Path public
     * Without /
     * @var string
     */
    const path_public = __DIR__ . '/../../../../public';

    /**
     * Path resources
     * Without /
     * @var string
     */
    const path_resources = __DIR__ . '/../../../../resources';

    /**
     * Path storage
     * Without /
     * @var string
     */
    const path_storage = __DIR__ . '/../../../../resources/Storage';

    /**
     * Main url
     * @var string
     */
    public static $url_root;

    /**
     * Public url
     * @var string
     */
    public static $url_public;

    /**
     * The GitHub API Url ( Without / )
     * The GitHub API access token
     * @var string
     */
    const GitHub_API_Url = 'https://api.github.com';

    /**
     * Constants constructor.
     */
    public function __construct()
    {
        // Get url
        $this->getUrl();
    }

    /**
     * Get url without parameters
     */
    private function getUrl()
    {
        // Protocol
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        // Get url, without / on the end
        self::$url_root = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        // Remove last / from url
        if (substr(self::$url_root, -1) == '/') {
            // Set new url
            self::$url_root = substr(self::$url_root, 0, -1);
        }
        // Set url with public, without / on the end
        self::$url_public = self::$url_root . '/public';
    }

}

?>
