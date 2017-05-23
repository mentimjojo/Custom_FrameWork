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


    public function create()
    {
        if(!isset($_COOKIE[$this->name])){

        } else {
            $return = array('status' => false,  'message' => 'cookie_exists');
        }
    }

    public static function get()
    {

    }

}

?>