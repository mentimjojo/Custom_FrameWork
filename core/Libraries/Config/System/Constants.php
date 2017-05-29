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
    const fw_version = "0.1.2.218";

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
     * Path core
     * Without /
     * @var string
     */
    const path_resources = __DIR__ . '/../../../Resources';

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
     * The updater url
     * @var string
     */
    const updater_url = 'https://fw-system.timnijborg.com';

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
        // Get url, without / on the end
        self::$url_root = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        // Remove last / from url
        if(substr(self::$url_root, -1) == '/'){
            // Set new url
            self::$url_root = substr(self::$url_root, 0, -1);
        }
        // Set url with public, without / on the end
        self::$url_public = self::$url_root . '/public';
    }

}

?>