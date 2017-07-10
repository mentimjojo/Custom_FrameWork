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
     *
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
     *
     * @param string $user_agent
     * @return $this
     */
    public function setUserAgent(string $user_agent){
        // Set user agent
        curl_setopt(self::$curl, CURLOPT_USERAGENT, $user_agent);
        // Return this
        return $this;
    }

    /**
     * Set post for the curl
     *
     * @param mixed $data
     * @return $this
     */
    public function setPOST($data)
    {
        if (!empty($data)) {
            // Set curl on post
            curl_setopt(self::$curl, CURLOPT_POST, true);
            // Send the data to the api
            curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $data);
        }
        // Return this
        return $this;
    }

    /**
     * Set http header
     *
     * @param array $header
     * @return $this
     */
    public function setHttpHeader(array $header){
        // Set header
        curl_setopt(self::$curl, CURLOPT_HTTPHEADER, $header);
        // Return this
        return $this;
    }

    /**
     * Follow location
     *
     * @param bool $follow
     * @return $this
     */
    public function followLocation(bool $follow){
        // Follow location
        curl_setopt(self::$curl, CURLOPT_FOLLOWLOCATION, $follow);
        // Return this
        return $this;
    }

    /**
     * Set return
     *
     * @param bool $return
     * @return $this
     */
    public function setReturn(bool $return)
    {
        // Set return
        curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, $return);
        // Return this
        return $this;
    }

    /**
     * Set ssl verify host
     *
     * @param bool $host
     * @return $this
     */
    public function setSSLVerifyHost(bool $host = true){
        // Set ssl
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYHOST, $host);
        // Return this
        return $this;
    }

    /**
     * Set ssl verify peer
     *
     * @param bool $peer
     * @return $this
     */
    public function setSSLVerifyPeer(bool $peer = true)
    {
        // Set ssl
        curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, $peer);
        // Return this
        return $this;
    }

    /**
     * Execute curl
     */
    public function execute()
    {
        // Execute curl
        self::$return = curl_exec(self::$curl);
        // Return this
        return $this;
    }

    /**
     * Get return
     *
     * @return mixed
     */
    public function getReturn(){
        // Get return
        return self::$return;
    }

    /**
     * Get the curl
     *
     * @return resource
     */
    public function get()
    {
        // Get the curl
        return self::$curl;
    }

}

?>