<?php

class Cookie
{

    /**
     * Cookie name
     * @var string
     */
    private $name;

    /**
     * Cookie value
     * @var string
     */
    private $value;

    /**
     * How long a cookie is valid
     * @var int
     */
    private $expire = 3600;

    /**
     * Path cookie
     * @var string
     */
    private $path = '/';

    /**
     * Set cookie name
     * @param string $name
     */
    public function setName(string $name)
    {
        // Save name
        $this->name = $name;
    }

    /**
     * Set cookie value
     * @param string $value
     */
    public function setValue(string $value)
    {
        // Save value
        $this->value = $value;
    }

    /**
     * Set expire of the cookie
     * @param int $expire
     */
    public function setExpire(int $expire = 3600)
    {
        // Set expire
        $this->expire = $expire;
    }

    /**
     * Set path of the cookie
     * @param string $path
     */
    public function setPath(string $path = '/')
    {
        // Save path
        $this->path = $path;
    }

    /**
     * Create cookie
     * @param bool $override
     * @return stdClass
     */
    public function create(bool $override = false): stdClass
    {
        if (!isset($_COOKIE[$this->name]) || $override) {
            // Check if name empty
            if (!empty($this->name)) {
                if (!empty($this->value)) {
                    if ($this->expire > 0) {
                        if (!empty($this->path)) {
                            // Create cookie
                            setcookie($this->name, $this->value, time() + $this->expire, $this->path);
                            // Return
                            $return = array('status' => true, 'message' => 'success');
                        } else {
                            // Return
                            $return = array('status' => false, 'message' => 'error_path_empty');
                        }
                    } else {
                        // Return
                        $return = array('status' => false, 'message' => 'error_expire_to_short');
                    }
                } else {
                    // Return
                    $return = array('status' => false, 'message' => 'error_value empty');
                }
            } else {
                // Return
                $return = array('status' => false, 'message' => 'error_name_empty');
            }
        } else {
            // Return
            $return = array('status' => false, 'message' => 'error_cookie_exists');
        }
        // Return
        return (object)$return;
    }

    /**
     * Delete cookie
     * @param string $name
     */
    public static function delete(string $name)
    {
        // Delete cookie
        setcookie($name, null, time() - 3600);
    }

    /**
     * Get cookie
     * @param string $name
     * @return null
     */
    public static function get(string $name)
    {
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        } else {
            return null;
        }
    }

}

?>