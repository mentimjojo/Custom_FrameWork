<?php
class Utils{

    /**
     * Header class
     * @var Header
     */
    public static $header;

    /**
     * Utils constructor.
     */
    public function __construct()
    {
        // Initialize header
        self::$header = new Header();
    }

}
?>