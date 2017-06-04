<?php

class Updater
{

    /**
     * Last version
     * @var int
     */
    private static $last_version;

    /**
     * Last changelog
     * @var string
     */
    private static $changelog;

    /**
     * Boolean if update needed
     * @var
     */
    private static $need_update = false;

    /**
     * Constructor is gonna check for us if there is a new version
     * Updater constructor.
     */
    private static function findUpdates()
    {
        // Check last version
        self::getLastVersion();
        // Check version
        self::checkVersion();
    }

    /**
     * Get last version of the framework
     */
    private static function getLastVersion()
    {
        // Get last version
        $lastVersionUrl = Constants::cfw_api_url . "/versions/releases.php";
        // Try
        try {
            /**
             * Get version
             */
            // Setup curl
            $curl_update = new Curl();
            // Set url
            $curl_update->setUrl($lastVersionUrl);
            // Set ssl
            $curl_update->setSSLVerifyPeer(false);
            // Set return
            $curl_update->setReturn(true);
            // Execute curl
            $versions = $curl_update->execute();
            // Check if empty
            if (empty($versions) || strpos($versions, 'Not Found') !== false) {
                // Send error
                ErrorHandler::warning(500, 'Something went wrong while trying to find new framework versions <br/> Error: ' . curl_errno($curl_update->get()) . curl_error($curl_update->get()));
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

            /**
             * Get changelog
             */
            // setup curl
            $curl_change = new Curl();
            // Set url
            $curl_change->setUrl(Constants::cfw_api_url . '/changelogs/fw-' . self::$last_version . '.txt');
            // Set ssl
            $curl_change->setSSLVerifyPeer(false);
            // Set return
            $curl_change->setReturn(true);
            // Execute
            $changelog = $curl_change->execute();
            // Check if empty or not exists
            if (empty($changelog) || strpos($changelog, 'Not Found') !== false) {
                // Do nothing
            } else {
                // Save changelog
                self::$changelog = $changelog;
            }
        } catch (Exception $ex) {
            // Send error
            ErrorHandler::warning(500, 'Something went wrong while trying to find new framework versions, error: ' . $ex->getMessage());
        }
    }

    /**
     * Check version
     */
    private static function checkVersion()
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
        // Find updates
        self::findUpdates();
        // Check if version is pulled
        if (!empty(self::$last_version)) {
            // Check if update needed
            if (self::$need_update) {
                // Get url
                $download_url = Constants::cfw_api_url . '/releases/fw-' . self::$last_version . '.zip';
                // Try
                try {
                    // Startup curl
                    $curl_down = new Curl();
                    // Set url
                    $curl_down->setUrl($download_url);
                    // Set return
                    $curl_down->setReturn(true);
                    // Set ssl
                    $curl_down->setSSLVerifyPeer(false);
                    // Execute
                    $download = $curl_down->execute();
                    // Check if empty
                    if (empty($download)) {
                        // Send error
                        ErrorHandler::warning(500, 'Something went wrong while trying to download the new framework version <br/> Error: ' . curl_errno($curl_down->get()) . curl_error($curl_down->get()));
                        // Send error
                        $return = array('status' => false, 'message' => 'update_error_see_log');
                    } else {
                        // Destination
                        $destination = Constants::path_resources . '/Updates/Update-' . self::$last_version . '.zip';
                        // Check dir exists
                        if (!is_dir(Constants::path_resources . '/Updates')) {
                            // If not create dir
                            mkdir(Constants::path_resources . '/Updates', 0777);
                        }
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
                $return = array('status' => false, 'message' => 'update_not_needed', 'installed_version' => Constants::fw_version, 'latest_version' => self::$last_version);
            }
        } else {
            // Return
            $return = array('status' => false, 'message' => 'update_no_version_found', 'installed_version' => Constants::fw_version, 'latest_version' => 'NULL');
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
        // Find updates
        self::findUpdates();
        // Check if version is pulled
        if (!empty(self::$last_version)) {
            // Check if needed
            if (self::$need_update) {
                // Set return
                $return = array('status' => true, 'message' => 'update_available', 'installed_version' => Constants::fw_version, 'latest_version' => self::$last_version, 'changelog' => self::$changelog);
            } else {
                // Set return
                $return = array('status' => false, 'message' => 'update_not_needed', 'installed_version' => Constants::fw_version, 'latest_version' => self::$last_version, 'changelog' => self::$changelog);
            }
        } else {
            $return = array('status' => false, 'message' => 'update_no_version_found', 'installed_version' => Constants::fw_version, 'latest_version' => 'NULL');
        }
        // Return
        return (object)$return;
    }

    /**
     * Return changelog
     * @return string
     */
    public static function getChangelog(): string
    {
        // Find updates
        self::findUpdates();
        // Return changelog
        return self::$changelog;
    }

    /**
     * Install the update
     * @return stdClass
     */
    public static function installUpdate(): stdClass
    {
        // Find updates
        self::findUpdates();
        // Check if version is pulled
        if (!empty(self::$last_version)) {
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
                        try {
                            // Extract zip/update
                            $update->extractTo(Constants::path_root);
                            // Close zip/update
                            $update->close();
                            // Update installed, now remove it
                            unlink(Constants::path_resources . '/Updates/Update-' . self::$last_version . '.zip');
                            // Set return
                            $return = array('status' => true, 'message' => "update_installed", 'changelog' => self::$changelog);
                            // Catch exception
                        } catch (Exception $ex) {
                            // Set return
                            $return = array('status' => false, 'message' => "update_failed_unknown", 'error' => $ex->getMessage());
                        }
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
                $return = array('status' => false, 'message' => 'update_not_needed', 'installed_version' => Constants::fw_version, 'latest_version' => self::$last_version);
            }
        } else {
            // Return
            $return = array('status' => false, 'message' => 'update_no_version_found', 'installed_version' => Constants::fw_version, 'latest_version' => 'NULL');
        }
        // Return update
        return (object)$return;
    }

}

?>