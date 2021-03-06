<?php

class ErrorHandler
{

    /**
     * ErrorHandler constructor.
     */
    public function __construct()
    {
        // Set Custom error handler
        set_error_handler(array($this, 'Error_Handler'), E_ALL);
    }

    /**
     * Create trace back
     * @param bool $glue
     * @return string
     */
    private static function generateCallTrace(bool $glue = true): string
    {
        // create new exception
        $e = new Exception();
        // Explode trace
        $trace = explode("\n", $e->getTraceAsString());
        // reverse array to make steps line up chronologically
        $trace = array_reverse($trace);
        // Remove main
        array_shift($trace);
        // Remove call to this method
        array_pop($trace);
        // Count
        $length = count($trace);
        // Create array
        $result = array();
        // Foreach as count
        for ($i = 0; $i < $length; $i++) {
            // Add to array
            $result[] = ($i + 1) . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
        }
        // Glue
        if ($glue) {
            // Set eol
            $glue = PHP_EOL;
        } else {
            // Set br
            $glue = '<br/>';
        }
        // Return
        return "\t" . implode($glue, $result);
    }

    /**
     * Log error message
     * @param int $code
     * @param string $message
     */
    public static function log(int $code, string $message)
    {
        // Error message
        $error = PHP_EOL . '[' . date('H:i:s d-m-Y', time()) . "] - Warning! Something went wrong with error code '" . $code . "' and message '" . $message . "'" . PHP_EOL . 'Trace back:' . PHP_EOL . self::generateCallTrace() . PHP_EOL;
        // Save error in file
        file_put_contents(Constants::path_resources . '/Logs/Errors/' . date('d-m-Y', time()) . '.txt', $error, FILE_APPEND);
    }

    /**
     * Warning
     * @param int $code
     * @param string $message
     */
    public static function warning(int $code, string $message)
    {
        // Check debug
        if (Constants::$debug) {
            // Get error template
            $template = file_get_contents(Constants::path_resources . '/Templates/Warning_Template_Debug.html');
            // Replace error code
            $template = str_replace('{Error_Code}', $code, $template);
            // Replace error trace back
            $template = str_replace('{Error_Trace_Back}', self::generateCallTrace(false), $template);
            // Replace error message
            $template = str_replace("{Error_Message}", $message, $template);
            // Echo warning
            echo $template;
        } else {
            // Error message
            $error = PHP_EOL . '[' . date('H:i:s d-m-Y', time()) . "] - Warning! Something went wrong with error code '" . $code . "' and message '" . $message . "'" . PHP_EOL . 'Trace back:' . PHP_EOL . self::generateCallTrace() . PHP_EOL;
            // Save error in file
            file_put_contents(Constants::path_resources . '/Logs/Errors/' . date('d-m-Y', time()) . '.txt', $error, FILE_APPEND);
        }
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
            $template = file_get_contents(Constants::path_resources . '/Templates/Error_Template_Debug.html');
            // Replace error code
            $template = str_replace('{Error_Code}', $code, $template);
            // Replace error trace back
            $template = str_replace('{Error_Trace_Back}', self::generateCallTrace(false), $template);
            // Replace error message
            $template = str_replace("{Error_Message}", $message, $template);
        } else {
            // Template
            $template = file_get_contents(Constants::path_resources . '/Templates/Error_Template.html');
            // Error message
            $error = PHP_EOL . '[' . date('H:i:s d-m-Y', time()) . "] - Fatal error! Something went wrong with error code '" . $code . "' and message '" . $message . "'" . PHP_EOL . 'Trace back:' . PHP_EOL . self::generateCallTrace() . PHP_EOL;
            // Save error in file
            file_put_contents(Constants::path_resources . '/Logs/Errors/' . date('d-m-Y', time()) . '.txt', $error, FILE_APPEND);
        }
        // Die site with template
        die($template);
    }

    /**
     * Custom error handler, if debug is enabled, error is shown direct. If debug is disabled error is stored in an error file.
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     */
    public function Error_Handler($errno, $errstr, $errfile, $errline)
    {
        // Report error when debug is enabled.
        if (Constants::$debug) {
            // Get error template
            $template = file_get_contents(Constants::path_resources . '/Templates/Warning_Message.html');
            // Replace error code
            $template = str_replace('{Error_Code}', $errno, $template);
            // Replace error message
            $template = str_replace("{Error_Message}", $errstr, $template);
            // Replace error line
            $template = str_replace('{Error_Line}', $errline, $template);
            // Replace error trace back
            $template = str_replace('{Error_Trace_Back}', self::generateCallTrace(false), $template);
            // Replace error file
            $template = str_replace("{Error_File}", $errfile, $template);
            // Echo $template
            echo $template;
        } else {
            // Error message
            $error = '[' . date('H:i:s d-m-Y', time()) . "] - Fatal error! Something went wrong on '" . $errfile . "' on line '" . $errline . "' with error code '" . $errno . "' and message '" . $errstr . "'" . PHP_EOL . 'Trace back:' . PHP_EOL . self::generateCallTrace() . PHP_EOL;
            // Save error in file
            file_put_contents(Constants::path_resources . '/Logs/Errors/' . date('d-m-Y', time()) . '.txt', $error, FILE_APPEND);
        }
    }

}

?>