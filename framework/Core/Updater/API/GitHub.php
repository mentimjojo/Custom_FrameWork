<?php

class Updater_API_GitHub
{

    /**
     * API url
     * @var string
     */
    private static $api_url;

    public function __construct()
    {
        // Setup API url ( Without / )
        self::$api_url = Constants::GitHub_API_Url . '/repos/mentimjojo/Custom_FrameWork';
    }

    /**
     * Create api url
     * @param string $url
     * @return string
     */
    private static function api_url(string $url): string
    {
        // Setup Access token + url
        return self::$api_url . '/' . $url . '?access_token=' . Constants::GitHub_API_Access_Token;
    }

    /**
     * Get latest release
     * @return mixed|null
     */
    private static function getLatestRelease()
    {
        // Setup Curl
        $curl = new Curl();
        // Set url
        $curl->setUrl(self::api_url('releases/latest'));
        // Set ssl
        $curl->setSSLVerifyPeer(true);
        // Set POST
        $curl->setUserAgent('Custom_FrameWork');
        // Set return
        $curl->setReturn(true);
        // Execute curl
        $curl->execute();
        // Return
        $release = json_decode($curl->getReturn());
        // Check if success
        if (!isset($release->message)) {
            // Return latest release
            return $release;
        } else {
            // Return false
            return null;
        }
    }

    /**
     * Get releases
     * @return mixed|null
     */
    private static function getReleases()
    {
        // Setup Curl
        $curl = new Curl();
        // Set url
        $curl->setUrl(self::api_url('releases'));
        // Set ssl
        $curl->setSSLVerifyPeer(true);
        // Set POST
        $curl->setUserAgent('Custom_FrameWork');
        // Set return
        $curl->setReturn(true);
        // Execute curl
        $curl->execute();
        // Return
        $releases = json_decode($curl->getReturn());
        // Check if success
        if (!isset($releases->message)) {
            // Return releases
            return $releases;
        } else {
            // Return false
            return null;
        }
    }

    /**
     * Get latest release
     * @return array|null
     */
    protected static function getLatest()
    {
        // Get latest release
        $release = self::getLatestRelease();
        // Check if success
        if ($release !== null) {
            // Latest
            $latest = array(
                'id' => $release->id,
                'version' => $release->tag_name,
                'name' => $release->name,
                'description' => $release->body
            );
            // Return latest
            return $latest;
        } else {
            return null;
        }
    }

    /**
     * Get current release
     * @return array
     */
    protected static function getCurrent()
    {
        // Get releases
        $releases = self::getReleases();
        // Check if success
        if ($releases !== null) {
            // Current release
            $current = array();
            // Find current release
            foreach ($releases as $release) {
                // Find release by version id
                if ($release->tag_name == Constants::fw_version) {
                    // Set release
                    $current = array(
                        'id' => $release->id,
                        'version' => $release->tag_name,
                        'name' => $release->name,
                        'description' => $release->body
                    );
                }
            }
            // Return release
            return $current;
        } else {
            // Return null
            return null;
        }
    }

    /**
     * Download update
     * @return object
     */
    protected static function downloadUpdate()
    {
        // Check if update needed
        $latest = self::getLatestRelease();
        // Check if not null
        if ($latest !== null) {
            // Object
            $latest = (object)$latest;
            // Check for update
            if ($latest->tag_name > Constants::fw_version) {
                // Try
                try {
                    // Setup Curl
                    $curl = new Curl();
                    // Set url
                    $curl->setUrl($latest->zipball_url . '?access_token=' . Constants::GitHub_API_Access_Token);
                    // Set POST
                    $curl->setUserAgent('Custom_FrameWork');
                    // Follow location
                    $curl->followLocation(true);
                    // Set return
                    $curl->setReturn(true);
                    // Set ssl
                    $curl->setSSLVerifyPeer(false);
                    // Execute curl
                    $curl->execute();
                    // Update
                    $download = $curl->getReturn();
                    // Check if empty
                    if (empty($download)) {
                        // Send error
                        ErrorHandler::warning(500, 'Something went wrong while trying to download the new framework version <br/> Error: ' . curl_errno($curl->get()) . curl_error($curl->get()));
                        // Set return
                        $return = array('status' => false, 'message' => 'error_download_error');
                    } else {
                        // Destination
                        $destination = Constants::path_storage . '/Updates/Update-' . $latest->tag_name . '.zip';
                        // Check dir exists
                        if (!is_dir(Constants::path_storage . '/Updates')) {
                            // If not create dir
                            mkdir(Constants::path_storage . '/Updates', 0777);
                        }
                        // Create file
                        $file = fopen($destination, "w+");
                        // Set data
                        fputs($file, $download);
                        // Close
                        fclose($file);
                        // Send success
                        $return = array('status' => true, 'message' => 'download_success');
                    }
                } catch (Exception $ex) {
                    // Send error
                    ErrorHandler::warning(500, 'Something went wrong while trying to find new framework versions, error: ' . $ex->getMessage());
                    // Send error
                    $return = array('status' => false, 'message' => 'error_unknown');
                }
            } else {
                // Set return
                $return = array('status' => false, 'message' => 'error_no_update_available');
            }
        } else {
            // Set return
            $return = array('status' => false, 'message' => 'error_unknown');
        }
        // Return
        return (object) $return;
    }


}

?>