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
    public static function unsetSession($name) {
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
     */
    public function createSession() {
        // check if session name exists
        if (!isset($_SESSION[$this->session_name])) {
            // Set session
            $_SESSION[$this->session_name] = $this->session_value;
        } else {
            // Throw error
            ErrorHandler::warning(117,'Session name duplicate found!');
        }
    }

}