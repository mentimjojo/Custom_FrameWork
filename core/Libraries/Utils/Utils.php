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
     * Mail system
     * @var Mail
     */
    public static $mail;

    /**
     * Upload system
     * @var Upload
     */
    public static $upload;

    /**
     * Utils constructor.
     */
    public function __construct()
    {
        // Initialize header
        self::$header = new Header();
        // Initialize misc
        self::$misc = new Misc();
        // Initialize mail
        self::$mail = new Mail();
        // Initialize upload
        self::$upload = new Upload();
    }

}
?>