<?php

class ErrorHandler
{

    /**
     * ErrorHandler constructor.
     */
    public function __construct()
    {
        // Set Custom error handler
        set_error_handler(array($this,  'Error_Handler'), E_ALL);
    }

    /**
     * Set debug
     * @param bool $toggle
     */
    public static function debug(bool $toggle = false)
    {
        // Set debug
        Constants::$debug = $toggle;
    }

    /**
     * Create error
     * @param int $code
     * @param string $message
     * @return array
     */
    public static function create(int $code, string $message): array
    {
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
    public static function die(int $code, string $message)
    {
        // Check debug
        if (Constants::$debug) {
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


    public function Error_Handler($errno, $errstr, $errfile, $errline)
    {
        // Report error when debug is enabled.
        if(Constants::$debug){
            // Get error template
            $template = file_get_contents(__DIR__ . '/Template/Error_Message.html');
            // Replace error code
            $template = str_replace('{Error_Code}', $errno, $template);
            // Replace error message
            $template = str_replace("{Error_Message}", $errstr, $template);
            // Replace error line
            $template = str_replace('{Error_Line}', $errline, $template);
            // Replace error file
            $template = str_replace("{Error_File}", $errfile, $template);
            // Echo $template
            echo $template;
        } else {
            // Error message
            $error = "Something went wrong on '" . $errfile . "' on line '" . $errline . "' with error code '" . $errno . "' and message '" . $errstr."'".PHP_EOL;
            // Send error
            file_put_contents(Constants::$path_root.'/logs/errors/' . date('d-m-y', time()), $error, FILE_APPEND);
        }
    }

}

?>