<?php
class Settings{

    /**
     * Set debug
     * @param bool $toggle
     */
    public static function setDebug(bool $toggle){
        Constants::$debug = $toggle;
    }

}
?>