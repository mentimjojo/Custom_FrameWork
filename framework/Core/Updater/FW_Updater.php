<?php

/**
 * Class FW_Updater
 * @Author T.Nijborg
 * @Version 0.1
 */
class FW_Updater extends Github_API
{

    /**
     * Set the access token for the Github API.
     *
     * @param string $token
     */
    public static function setAccessToken(string $token){
        // Set the access token
        parent::$api_access_token = $token;
    }

    /**
     * Get current
     *
     * @return object|null
     */
    public static function getCurrent()
    {
        return parent::getCurrent();
    }

    /**
     * Get latest
     *
     * @return object|null
     */
    public static function getLatest()
    {
        return parent::getLatest();
    }

    /**
     * Get one release
     *
     * @param string $version
     * @return stdClass
     */
    public static function getOneRelease(string $version): stdClass
    {
        // Get one release
        return self::getReleases(true)->$version;
    }

    /**
     * Get upcoming release
     *
     * @return stdClass
     */
    public static function getUpcomingRelease(): stdClass
    {
        // Releases
        $releases = self::getReleases(true);
        // Upcoming release
        $upcoming_release = array();
        // Foreach release
        foreach ($releases as $release) {
            if ($release->pre_release) {
                $upcoming_release = (object)array(
                    'id' => $release->id,
                    'name' => $release->name,
                    'version' => $release->version,
                    'description' => $release->description
                );
            }
        }
        // Return array (object)
        return (object)$upcoming_release;
    }

    /**
     * Get all releases
     *
     * @param bool $upcoming true/false if the releases also shows upcoming release(s)
     * @return stdClass
     */
    public static function getReleases(bool $upcoming = false): stdClass
    {
        // Releases
        $releases = parent::getReleases();
        // Array with releases
        $array_releases = array();
        // Foreach release
        foreach ($releases as $release) {
            if ($release->prerelease && !$upcoming) {
                // Skip this release
                continue;
            }
            // Put release in array
            $array_releases[$release->tag_name] = (object)array(
                'id' => $release->id,
                'name' => $release->name,
                'version' => $release->tag_name,
                'description' => $release->body,
                'pre_release' => $release->prerelease
            );
        }
        // Return array (object)
        return (object)$array_releases;
    }


    /**
     * Find update
     *
     * @return null|stdClass
     */
    public static function findUpdate() : ?stdClass
    {
        // Latest
        $latest = parent::getLatest();
        // Check not null
        if ($latest != null) {
            // Check if update needed
            if ($latest->version > Constants::fw_version) {
                return (object)array('update_available' => true, 'current_version' => Constants::fw_version, 'latest_version' => $latest->version, 'changelog' => $latest->description);
            } else {
                return (object)array('update_available' => false, 'current_version' => Constants::fw_version, 'latest_version' => $latest->version);
            }
        } else {
            // Return null
            return null;
        }
    }

    /**
     * Install update
     *
     * @return stdClass
     */
    public static function installUpdate(): stdClass
    {
        // Find update
        $update = self::findUpdate();
        // Check if not null
        if ($update !== null) {
            // Check if update needed
            if ($update->update_available) {
                // Download update
                $download = parent::downloadUpdate();
                // Temp folder
                $temp_folder = Constants::path_storage . '/Updates/Temp';
                // Check if success
                if ($download->status) {
                    // Check dir exists
                    if (!is_dir($temp_folder)) {
                        // If not create dir
                        mkdir($temp_folder, 0777);
                    }
                    // Zip archive
                    $zip = new ZipArchive();
                    // Open
                    if ($zip->open(Constants::path_storage . '/Updates/Update-' . $update->latest_version . '.zip') === true) {
                        try {
                            // Extract to
                            $zip->extractTo($temp_folder);
                            // Close zip/update
                            $zip->close();

                            // Update extracted, now remove it
                            unlink(Constants::path_storage . '/Updates/Update-' . $update->latest_version . '.zip');
                            // Now find dir where install is placed temp
                            $dir = array_diff(scandir($temp_folder), array('.', '..'));
                            // Foreach in temp dir
                            foreach ($dir as $dir_temp) {
                                // Check if update folder
                                if (is_dir($temp_folder . '/' . $dir_temp)) {
                                    // Check public folder
                                    if (is_dir($temp_folder . '/' . $dir_temp . '/public')) {
                                        // Delete public folder
                                        system("rm -rf " . escapeshellarg($temp_folder . '/' . $dir_temp . '/public'));
                                    }
                                    // Check gitignore
                                    if (file_exists($temp_folder . '/' . $dir_temp . '/.gitignore')) {
                                        // Delete gitignore
                                        unlink($temp_folder . '/' . $dir_temp . '/.gitignore');
                                    }
                                    // Check readme
                                    if (file_exists($temp_folder . '/' . $dir_temp . '/README.md')) {
                                        // Delete readme
                                        unlink($temp_folder . '/' . $dir_temp . '/README.md');
                                    }

                                    // Create new install zip
                                    Utils::$zip->create($temp_folder . '/' . $dir_temp . '/', Constants::path_storage . '/Updates/Update-' . $update->latest_version . '.zip');
                                    // Delete temp update, check first if exists
                                    if (is_dir($temp_folder . '/' . $dir_temp)) {
                                        // Delete public folder
                                        system("rm -rf " . escapeshellarg($temp_folder . '/' . $dir_temp));
                                    }
                                }
                            }
                            // Now lets install
                            $install = new ZipArchive();
                            // Open zip
                            if ($install->open(Constants::path_storage . '/Updates/Update-' . $update->latest_version . '.zip') === true) {
                                // Extract to
                                $install->extractTo(Constants::path_root);
                                // Close zip/update
                                $install->close();
                                // Update extracted, now remove it
                                unlink(Constants::path_storage . '/Updates/Update-' . $update->latest_version . '.zip');
                                // Set return
                                $return = array('status' => true, 'message' => 'success', 'error' => 'Update installed successfully');
                            } else {
                                // Set return
                                $return = array('status' => false, 'message' => 'update_failed', 'error' => 'Update could not be opened.');
                            }
                        } catch (Exception $ex) {
                            // Set return
                            $return = array('status' => false, 'message' => "update_failed_unknown", 'error' => $ex->getMessage());
                        }
                    } else {
                        // Set return
                        $return = array('status' => false, 'message' => 'update_failed', 'error' => 'Update could not be opened.');
                    }
                } else {
                    // set Return
                    $return = array('status' => false, 'message' => 'error_download_failed', 'download_error' => $download);
                }
            } else {
                // set Return
                $return = array('status' => false, 'message' => 'error_no_update_available');
            }
        } else {
            // Set return
            $return = array('status' => false, 'message' => 'error_unknown');
        }

        return (object)$return;
    }

}

?>