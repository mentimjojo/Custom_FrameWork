<?php

class Upload
{

    /**
     * File(s) types allowed
     * @var array
     */
    private $file_types = array();

    /**
     * Set minimal size of the file(s). Default is 0
     * Size in Bytes
     * @var int
     */
    private $min_size = 0;

    /**
     * Max size of the file(s), default is 50MB
     * Size in Bytes
     * @var int
     */
    private $max_size = 52428800;

    /**
     * Set the minimal files to upload. Default is 0
     * @var int
     */
    private $min_files = 0;

    /**
     * Max files to upload. Default is 10
     * @var int
     */
    private $max_files = 10;

    /**
     * The upload path
     * @var string
     */
    private $upload_path;

    /**
     * Return
     * @var
     */
    private $return;

    /**
     * Set path where to upload the files to. Path is automatically inside Resources/Storage folder.
     *
     * @param string $path
     * @return $this
     */
    public function setUploadPath(string $path)
    {
        // Set path to upload. Path is automatically in public folder
        $this->upload_path = Constants::path_storage . '/Files/' . $path;
        // Check if exists
        if(!is_dir($this->upload_path)){
            // Create dir
            mkdir($this->upload_path);
        }
        // Return this object
        return $this;
    }

    /**
     * Set the minimum size of the file(s)
     *
     * @param int $size in MB's
     * @return $this
     */
    public function setMinSize(int $size = 50)
    {
        // Set min size
        $this->min_size = $size * 1048576;
        // Return this object
        return $this;
    }

    /**
     * Set the maximum size of the file(s)
     *
     * @param int $size in MB's
     * @return $this
     */
    public function setMaxSize(int $size = 50)
    {
        // Set max size
        $this->max_size = $size * 1048576;
        // Return this object
        return $this;
    }

    /**
     * Set the minimum count of files.
     *
     * @param int $min
     * @return $this
     */
    public function setMinFiles(int $min = 0)
    {
        // Set min files
        $this->min_files = $min;
        // Return this object
        return $this;
    }

    /**
     * Set the maximum count of files.
     *
     * @param int $max
     * @return $this
     */
    public function setMaxFiles(int $max = 10)
    {
        // Set max files
        $this->max_files = $max;
        // Return this object
        return $this;
    }

    /**
     * Set the file types allowed.
     *
     * @param array $types
     * @return $this
     */
    public function setFileTypes(array $types)
    {
        // Fix uppercase letters
        foreach ($types as $type) {
            $types[] = strtolower($type);
        }
        // Set the file types
        $this->file_types = $types;
        // Return this object
        return $this;
    }

    /**
     * Upload files
     *
     * @param array $upload
     */
    public function send(array $upload)
    {
        // Check multiple
        if ($this->max_files > 1) {
            $this->return = $this->upload_multiple($upload);
        } else {
            $this->return = $this->upload_one($upload);
        }
    }

    /**
     * Get return of the upload
     *
     * @return mixed
     */
    public function getReturn(){
        // Return
        return $this->return;
    }

    /**
     * Upload one file
     *
     * @param array $file
     * @return stdClass
     */
    private function upload_one(array $file): stdClass
    {
        // Make file object
        $file = (object)$file['file'];
        // Target name
        $target_name = Utils::$misc->generateRandomString(rand(25, 50), '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') . '-' . $file->name;
        // Target file
        $target_file = $this->upload_path . '/' . $target_name;
        // Get file type
        $target_type = pathinfo($target_file, PATHINFO_EXTENSION);
        // Check exists first
        if (!file_exists($target_file)) {
            // Check if file type is allowed
            if (in_array(strtolower($target_type), $this->file_types)) {
                // Check file size to big
                if ($file->size <= $this->max_size) {
                    // Check file size to small
                    if ($file->size >= $this->min_size) {
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
     *
     * @param array $files
     * @return stdClass
     */
    private function upload_multiple(array $files): stdClass
    {
        // Original files
        $original_files = array();
        // Check not minimum count
        if (count($files['name']) >= $this->min_files) {
            // Check not maximum count
            if (count($files['name']) <= $this->max_files) {
                // Re-array files
                $files = $this->reArrayFiles($files);
                // Set return null
                $return = array();
                // Foreach file
                foreach ($files as $file) {
                    // Check return empty
                    if(!empty($return)){
                        // Check if error happened
                        if(!$return['status']) {
                            // Break foreach
                            break;
                        }
                    }
                    // Set file as object
                    $file = (object)$file;
                    // Target name
                    $target_name = Utils::$misc->generateRandomString(rand(25, 50), '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') . '-' . $file->name;
                    // Target file
                    $target_file = $this->upload_path . '/' . $target_name;
                    // Get file type
                    $target_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    // Check exists first
                    if (!file_exists($target_file)) {
                        // Check if file type is allowed
                        if (in_array(strtolower($target_type), $this->file_types)) {
                            // Check file size to big
                            if ($file->size <= $this->max_size) {
                                // Check file size to small
                                if ($file->size >= $this->min_size) {
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
            } else {
                // Set return
                $return = array('status' => false, 'message' => 'error_file_maximum_count');
            }
        } else {
            // Set return
            $return = array('status' => false, 'message' => 'error_file_minimum_count');
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
     *
     * @param array $file_post files
     * @return array the array
     */
    private function reArrayFiles(array &$file_post): array
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