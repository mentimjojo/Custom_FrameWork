<?php
/**
 * Custom Framework
 * @Author R.Bruil
 * @Clean_Up T.Nijborg
 **/
class Session {

    /**
     * Session name.
     * @var string
     */
    private $session_name;

    /**
     * Session value
     * @var string
     */
    private $session_value;

    /**
     * Set the session name
     * @param string $name
     */
    public function setSessionName(string $name) {
        // set session name
        $this->session_name = $name;
    }

    /**
     * Set the session value
     * @param string $value
     */
    public function setSessionValue(string $value) {
        // set session time
        $this->session_value = $value;
    }

    /**
     * Unset session
     * @param $name
     */
    public static function unsetSession(string $name) {
        // unset session $name
        unset($_SESSION[$name]);
    }

    /**
     * Destroy session
     * @deprecated
     */
    public function destroySession() {
        session_destroy();
    }

    /**
     * Create session
     * @param bool $override override the existing session
     * @return stdClass
     */
    public function createSession(bool $override = false) : stdClass {
        // if name empty
        if(!empty($this->session_name)) {
            // If empty value
            if(!empty($this->session_value)) {
                // check if session name exists
                if (!isset($_SESSION[$this->session_name]) || $override) {
                    // Set session
                    $_SESSION[$this->session_name] = $this->session_value;
                    // Set return
                    $return = array('status' => true, 'message' => 'success');
                } else {
                    // Throw error
                    $return = array('status' => false, 'message' => 'error_duplicate_name');
                }
            } else {
                // Set return
                $return = array('status' => false, 'message' => 'error_empty_value');
            }
        } else {
            // Set return
            $return = array('status' => false, 'message' => 'error_empty_name');
        }
        // Return
        return (object) $return;
    }

    /**
     * Get class
     * @param string $name
     * @return stdClass
     */
    public static function get(string $name) : stdClass{
        // Get session
        if(isset($_SESSION[$name])){
            // Set return
            $return = array('status' => true, 'message' => 'success', 'session' => $_SESSION[$name]);
        } else {
            // Set return
            $return = array('status' => false, 'message' => 'session_not_found');
        }
        // Return
        return (object) $return;
    }

}