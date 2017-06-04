<?php

class Curl
{

    /**
     * The curl to be used
     */
    private static $curl;

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
     * Set post for the curl
     * @param mixed $data
     */
    public function setPOST(mixed $data){
        if(!empty($data)){
            // Set curl on post
            curl_setopt(self::$curl, CURLOPT_POST, true);
            // Send the data to the api
            curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $data);
        }
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
     * @return mixed
     */
    public function execute() : mixed{
        // Execute curl
        $execute = curl_exec(self::$curl);
        // Return execute
        return $execute;
    }

    /**
     * Get the curl
     * @return resource
     */
    public function get(){
        // Get the curl
        return self::$curl;
    }

}

?>