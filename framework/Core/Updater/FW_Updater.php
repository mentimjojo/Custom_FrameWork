<?php

/**
 * Class FW_Updater
 * @Author T.Nijborg
 * @Version 0.1
 */
class FW_Updater extends Updater_API_GitHub
{

    /**
     * Get current
     * @return array
     */
    public static function getCurrent()
    {
        return parent::getCurrent();
    }

    /**
     * Get latest
     * @return array|null
     */
    public static function getLatest()
    {
        return parent::getLatest();
    }

    /**
     * Find update
     * @return object|null
     */
    public static function findUpdate()
    {
        // Latest
        $latest = parent::getLatest();
        // Check not null
        if ($latest != null) {
            // Check if update needed
            if ($latest->version > Constants::fw_version) {
                return (object) array('update_available' => true, 'current_version' => Constants::fw_version, 'newest_version' => $latest->version);
            } else {
                return (object) array('update_available' => false, 'current_version' => Constants::fw_version, 'newest_version' => $latest->version);
            }
        } else {
            // Return null
            return null;
        }
    }

    /**
     * Install update
     * @return object
     */
    public static function installUpdate()
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
                    if ($zip->open(Constants::path_storage . '/Updates/Update-' . $update->newest_version . '.zip') === true) {
                        try {
                            // Extract to
                            $zip->extractTo($temp_folder);
                            // Close zip/update
                            $zip->close();

                            // Update extracted, now remove it
                            unlink(Constants::path_storage . '/Updates/Update-' . $update->newest_version . '.zip');
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
                                    Utils::$zip->create($temp_folder . '/' . $dir_temp . '/', Constants::path_storage . '/Updates/Update-' . $update->newest_version . '.zip');
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
                            if ($install->open(Constants::path_storage . '/Updates/Update-' . $update->newest_version . '.zip') === true) {
                                // Extract to
                                $install->extractTo(Constants::path_root);
                                // Close zip/update
                                $install->close();
                                // Update extracted, now remove it
                                unlink(Constants::path_storage . '/Updates/Update-' . $update->newest_version . '.zip');
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

        return (object) $return;
    }

}

?>