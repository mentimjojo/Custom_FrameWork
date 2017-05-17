<?php

class Upload
{

    /**
     * File(s) types allowed
     * @var array
     */
    private static $file_types = array();

    /**
     * Set minimal size of the file(s). Default is 0
     * Size in KB's
     * @var int
     */
    private static $min_size = 0;

    /**
     * Max size of the file(s), default is 50MB
     * Size in KB's
     * @var int
     */
    private static $max_size = 51200;

    /**
     * Set the minimal files to upload. Default is 0
     * @var int
     */
    private static $min_files = 0;

    /**
     * Max files to upload. Default is 10
     * @var int
     */
    private static $max_files = 10;

    /**
     * If user can upload multiple or not. Default is false
     * @var bool
     */
    private static $multiple = false;

    /**
     * The upload path
     * @var string
     */
    private static $upload_path;

    /**
     * Set path where to upload the files to. Path is automatically inside public folder.
     * @param string $path
     */
    public static function setUploadPath(string $path)
    {
        // Set path to upload. Path is automatically in public folder
        self::$upload_path = Constants::path_public . '/' . $path;
    }

    /**
     * Set the minimum size of the file(s)
     * @param int $size
     */
    public static function setMinSize(int $size = 50)
    {
        // Set max size
        self::$min_size = $size * 1024;
    }

    /**
     * Set the maximum size of the file(s)
     * @param int $size
     */
    public static function setMaxSize(int $size = 50)
    {
        // Set max size
        self::$max_size = $size * 1024;
    }

    /**
     * Set the minimum count of files.
     * @param int $min
     */
    public static function setMinFiles(int $min = 0)
    {
        // Set max files
        self::$min_files = $min;
    }

    /**
     * Set the maximum count of files.
     * @param int $max
     */
    public static function setMaxFiles(int $max = 10)
    {
        // Set max files
        self::$max_files = $max;
    }

    /**
     * Set the file types allowed.
     * @param array $types
     */
    public static function setFileTypes(array $types)
    {
        // Fix uppercase letters
        foreach ($types as $type) {
            $types[] = strtolower($type);
        }
        // Set the file types
        self::$file_types = $types;
    }

    /**
     * Set if user can upload multiple files at the same time or not.
     * @param bool $toggle
     */
    public static function multiple(bool $toggle = false)
    {
        // Set multiple files or not
        self::$multiple = $toggle;
    }

    /**
     * Upload files
     * @param array $upload
     * @return stdClass
     */
    public static function send(array $upload)
    {
        // Check multiple
        if(self::$multiple){
            $return = self::upload_multiple($upload);
        } else {
            $return = self::upload_one($upload);
        }
        // Return array
        return $return;
    }

    /**
     * Upload one file
     * @param array $file
     * @return stdClass
     */
    private static function upload_one(array $file): stdClass
    {
        // Make file object
        $file = (object)$file;
        // Target name
        $target_name = Utils::$misc->generateRandomString(rand(25, 50)) . '-' . $file->name;
        // Target file
        $target_file = self::$upload_path . '/' . $target_name;
        // Get file type
        $target_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check exists first
        if (!file_exists($target_file)) {
            // Check if file type is allowed
            if (in_array($target_type, self::$file_types)) {
                // Check file size to big
                if ($file->size <= self::$max_size) {
                    // Check file size to small
                    if ($file->size >= self::$min_size) {
                        // Move file to location
                        if (move_uploaded_file($file->tmp_name, $target_file)) {
                            // Set return
                            $return = array('status' => true, 'message' => 'success', 'file_data' => array(
                                'name' => $file->name,
                                'target_name' => $target_name,
                                'size' => $file->size
                            ));
                        } else {
                            // Set return
                            $return = array('status' => false, 'message' => 'error_unknown');
                        }
                    } else {
                        // Set return
                        $return = array('status' => false, 'message' => 'error_file_to_small');
                    }
                } else {
                    // Set return
                    $return = array('status' => false, 'message' => 'error_file_to_big');
                }
            } else {
                // Set return
                $return = array('status' => false, 'message' => 'error_file_type');
            }
        } else {
            // Set return
            $return = array('status' => false, 'message' => 'error_file_exists');
        }
        // Return array
        return (object)$return;
    }

    /**
     * Upload multiple files
     * @param array $files
     * @return stdClass
     */
    private static function upload_multiple(array $files): stdClass
    {
        // Original files
        $original_files = array();
        // Re-array files
        $files = self::reArrayFiles($files);
        // Set return null
        $return = array();
        // Foreach file
        foreach ($files as $file) {
            // Set file as object
            $file = (object)$file;
            // Target name
            $target_name = Utils::$misc->generateRandomString(rand(25, 50)) . '-' . $file->name;
            // Target file
            $target_file = self::$upload_path . '/' . $target_name;
            // Get file type
            $target_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // Check exists first
            if (!file_exists($target_file)) {
                // Check if file type is allowed
                if (in_array($target_type, self::$file_types)) {
                    // Check file size to big
                    if ($file->size <= self::$max_size) {
                        // Check file size to small
                        if ($file->size >= self::$min_size) {
                            // Move file to location
                            if (move_uploaded_file($file->tmp_name, $target_file)) {
                                // Set in array
                                array_push($original_files, array(
                                    'name' => $file->name,
                                    'target_name' => $target_name,
                                    'size' => $file->size
                                ));
                                // Set return
                                $return = array('status' => true, 'message' => 'success');
                            } else {
                                // Set return
                                $return = array('status' => false, 'message' => 'error_unknown');
                            }
                        } else {
                            // Set return
                            $return = array('status' => false, 'message' => 'error_file_to_small');
                        }
                    } else {
                        // Set return
                        $return = array('status' => false, 'message' => 'error_file_to_big');
                    }
                } else {
                    // Set return
                    $return = array('status' => false, 'message' => 'error_file_type');
                }
            } else {
                // Set return
                $return = array('status' => false, 'message' => 'error_file_exists');
            }
        }
        // Check if set
        if (!empty($original_files)) {
            $return['files'] = (object)$original_files;
        }
        // Return
        return (object)$return;
    }

    /**
     * Re-array files
     * @param array $file_post files
     * @return array the array
     */
    private static function reArrayFiles(array &$file_post): array
    {
        // Create array
        $file_ary = array();
        // Count files
        $file_count = count($file_post['name']);
        // Get file keys
        $file_keys = array_keys($file_post);
        // Re-array
        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }
        // Re-array files
        return $file_ary;
    }
}

?>