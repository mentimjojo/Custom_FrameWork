<?php

class Updater
{

    /**
     * Last version
     * @var int
     */
    private static $last_version;

    /**
     * Boolean if update needed
     * @var
     */
    private static $need_update = false;

    /**
     * Constructor is gonna check for us if there is a new version
     * Updater constructor.
     */
    public function __construct()
    {
        // Check last version
        $this->getLastVersion();
        // Check version
        $this->checkVersion();
    }

    /**
     * Get last version of the framework
     */
    private function getLastVersion()
    {
        // Get last version
        $lastVersionUrl = Constants::updater_url . "/config/version.php";
        // Try
        try {
            // Startup curl
            $curl = curl_init();
            // Set url
            curl_setopt($curl, CURLOPT_URL, $lastVersionUrl);
            // Set return
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // Set ssl false
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            // Get version
            $versions = curl_exec($curl);
            // Check if empty
            if (empty($versions)) {
                // Send error
                ErrorHandler::warning(500, 'Something went wrong while trying to find new framework versions <br/> Error: ' . curl_errno($curl) . curl_error($curl));
            } else {
                // All versions
                $versionList = explode("\n", $versions);
                // Last version
                $last = '';
                // Check for version last
                foreach ($versionList as $ver) {
                    $last = $ver;
                }
                // Set last version
                self::$last_version = $last;
            }
        } catch (Exception $ex) {
            // Send error
            ErrorHandler::warning(500, 'Something went wrong while trying to find new framework versions, error: ' . $ex->getMessage());
        }
    }

    /**
     * Check version
     */
    private function checkVersion()
    {
        // Check if update needed
        if (Constants::fw_version < self::$last_version) {
            // Set update needed
            self::$need_update = true;
        }
    }

    /**
     * Download update
     * @return stdClass
     */
    private static function downloadUpdate(): stdClass
    {
        if (self::$need_update) {
            // Get url
            $download_url = Constants::updater_url . '/releases/fw-' . self::$last_version . '.zip';
            // Try
            try {
                // Startup curl
                $curl = curl_init();
                // Set url
                curl_setopt($curl, CURLOPT_URL, $download_url);
                // Set return
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                // Set ssl false
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                // Get download
                $download = curl_exec($curl);
                // Check if empty
                if (empty($download)) {
                    // Send error
                    ErrorHandler::warning(500, 'Something went wrong while trying to download the new framework version <br/> Error: ' . curl_errno($curl) . curl_error($curl));
                    // Send error
                    $return = array('status' => false, 'message' => 'update_error_see_log');
                } else {
                    // Destination
                    $destination = Constants::path_resources . '/Updates/Update-' . self::$last_version . '.zip';
                    // Create file
                    $file = fopen($destination, "w+");
                    // Set data
                    fputs($file, $download);
                    // Close
                    fclose($file);
                    // Send error
                    $return = array('status' => true, 'message' => 'download_success');
                }
            } catch (Exception $ex) {
                // Send error
                ErrorHandler::warning(500, 'Something went wrong while trying to find new framework versions, error: ' . $ex->getMessage());
                // Send error
                $return = array('status' => false, 'message' => 'update_error_see_log');
            }
        } else {
            // Set return
            $return = array('status' => false, 'message' => 'update_not_needed');
        }
        // Return
        return (object)$return;
    }

    /**
     * Send back needed info
     * @return stdClass
     */
    public static function info(): stdClass
    {
        // Check if needed
        if (self::$need_update) {
            // Set return
            $return = array('status' => true, 'message' => 'update_available', 'installed_version' => Constants::fw_version, 'latest_version' => self::$last_version);
        } else {
            // Set return
            $return = array('status' => false, 'message' => 'update_not_needed', 'installed_version' => Constants::fw_version,  'latest_version' => self::$last_version);
        }
        // Return
        return (object)$return;
    }

    /**
     * Install the update
     * @return stdClass
     */
    public static function installUpdate() : stdClass
    {
        // check if needed
        if (self::$need_update) {
            // Download update
            $download = self::downloadUpdate();
            // Check if download success
            if ($download->status) {
                // Create new zip Archive
                $update = new ZipArchive();
                // Open zip/update
                if ($update->open(Constants::path_resources . '/Updates/Update-' . self::$last_version . '.zip') === TRUE) {
                    // Extract zip/update
                    $update->extractTo(Constants::path_root);
                    // Close zip/update
                    $update->close();
                    // Set return
                    $return = array('status' => true, 'message' => "update_installed");
                } else {
                    // Set return
                    $return = array('status' => false, 'message' => 'update_failed', 'error' => 'Update could not be opened.');
                }
            } else {
                // Set return
                $return = array('status' => false, 'message' => 'update_failed', 'error' => $download->message);
            }
        } else {
            // Set return
            $return = array('status' => false, 'message' => 'update_not_needed', 'installed_version' => Constants::fw_version,  'latest_version' => self::$last_version);
        }
        // Return update
        return (object) $return;
    }

}

?>