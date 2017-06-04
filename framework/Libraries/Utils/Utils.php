<?php
class Utils{

    /**
     * Header class
     * @var Header
     */
    public static $header;

    /**
     * Misc
     * @var Misc
     */
    public static $misc;

    /**
     * Utils constructor.
     */
    public function __construct()
    {
        // Initialize header
        self::$header = new Header();
        // Initialize misc
        self::$misc = new Misc();
    }

}
?>