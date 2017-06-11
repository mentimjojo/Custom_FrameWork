<?php

class Curl
{

    /**
     * The curl to be used
     */
    private static $curl;

    /**
     * Return
     * @var mixed
     */
    private static $return;

    /**
     * Setup curl
     * Curl constructor.
     */
    public function __construct()
    {
        // Setup curl
        self::$curl = curl_init();
    }

    /**
     * Set url of the curl
     * @param string $url
     * @return stdClass
     */
    public function setUrl(string $url): stdClass
    {
        // Check if url is empty
        if (!empty($url)) {
            // Check if curl is url
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                // Set url
                curl_setopt(self::$curl, CURLOPT_URL, $url);
                // Return
                $return = array('status' => true, 'message' => 'success');
            } else {
                // Return
                $return = array('status' => false, 'message' => 'error_url_not_valid');
            }
        } else {
            // Return
            $return = array('status' => false, 'message' => 'error_url_empty');
        }
        // Return
        return (object)$return;
    }

    /**
     * Set user agent
     * @param string $user_agent
     */
    public function setUserAgent(string $user_agent){
        // Set user agent
        curl_setopt(self::$curl, CURLOPT_USERAGENT, $user_agent);
    }

    /**
     * Set post for the curl
     * @param mixed $data
     */
    public function setPOST($data)
    {
        if (!empty($data)) {
            // Set curl on post
            curl_setopt(self::$curl, CURLOPT_POST, true);
            // Send the data to the api
            curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $data);
        }
    }

    /**
     * Follow location
     * @param bool $follow
     */
    public function followLocation(bool $follow){
        // Follow location
        curl_setopt(self::$curl, CURLOPT_FOLLOWLOCATION, $follow);
    }

    /**
     * Set return
     * @param bool $return
     */
    public function setReturn(bool $return)
    {
        // Set return
        curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, $return);
    }

    /**
     * Set ssl on/off
     * @param bool $ssl
     */
    public function setSSLVerifyPeer(bool $ssl = true)
    {
        // Set ssl
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, $ssl);
    }

    /**
     * Execute curl
     */
    public function execute()
    {
        // Execute curl
        self::$return = curl_exec(self::$curl);
    }

    /**
     * Get return
     * @return mixed
     */
    public function getReturn(){
        // Get return
        return self::$return;
    }

    /**
     * Get the curl
     * @return resource
     */
    public function get()
    {
        // Get the curl
        return self::$curl;
    }

}

?>