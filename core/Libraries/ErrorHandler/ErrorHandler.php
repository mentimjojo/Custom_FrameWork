<?php
class ErrorHandler {

    /**
     * Set debug
     * @param bool $toggle
     */
    public static function debug(bool $toggle = false){
        // Set debug
        Constants::$debug = $toggle;
    }

    /**
     * Create error
     * @param int $code
     * @param string $message
     * @return array
     */
    public static function create(int $code, string $message) : array {
        // Return error
        return array(
            "code" => $code,
            "message" => $message
        );
    }

    /**
     * Die page with some layout
     * @param int $code
     * @param string $message
     */
    public static function die(int $code, string $message) {
        // Check debug
        if(Constants::$debug) {
            // Get error template
            $template = file_get_contents(__DIR__ . '/Template/Error_Template_Debug.html');
            // Replace error code
            $template = str_replace('{Error_Code}', $code, $template);
            // Replace error message
            $template = str_replace("{Error_Message}", $message, $template);
        } else {
            $template = file_get_contents(__DIR__ . '/Template/Error_Template.html');
        }
        // Die site with template
        die($template);
    }

}
?>