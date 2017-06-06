<?php

class download
{

    /**
     * Name of the download
     * @var string
     */
    private $name;

    /**
     * Type of the file
     * @var string
     */
    private $type;

    /**
     * File to download.
     * @var
     */
    private $file;

    /**
     * Set the name of a user.
     * @param string $name
     */
    public function setName(string $name)
    {
        // Set name
        $this->name = $name;
    }

    /**
     * Set file to download, path is automatically in Resources/Storage folder.
     * @param string $path
     */
    public function setFile(string $path)
    {
        // Set file to download
        $this->file = Constants::path_storage . '/Files/' . $path;
        // Save file type
        $this->type = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }

    /**
     * Set headers for the download
     */
    private function setHeaders()
    {
        // Set content file transfer
        header('Content-Description: File Transfer');
        // Set type file
        header('Content-Type: application/octet-stream');
        // Set name for the file
        header('Content-Disposition: attachment; filename=' . basename($this->name . '.' . $this->type));
        // Set encoding
        header('Content-Transfer-Encoding: binary');
        // Set expires
        header('Expires: 0');
        // Set cache control
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        // Set public
        header('Pragma: public');
        // Set file itself
        header('Content-Length: ' . filesize($this->file));
        // Clean ob
        echo $this->file;
        ob_clean();
        // Flush
        flush();
        // Read file for download
        readfile($this->file);
        // Exit
        exit;
    }

    /**
     * Download the file
     * @return stdClass
     */
    public function start(): stdClass
    {
        // Check if not empty
        if (!empty($this->name)) {
            if (!empty($this->file)) {
                // Set headers
                $this->setHeaders();
                // Return
                $return = array('status' => true, 'message' => 'success');
            } else {
                $return = array('status' => false, 'message' => 'error_file_empty');
            }
        } else {
            $return = array('status' => false, 'message' => 'error_name_empty');
        }
        // Return object
        return (object)$return;
    }

}

?>