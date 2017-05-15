<?php
class Config {

    /**
     * Config constructor.
     */
    public function __construct()
    {
        // Setup Constants
        new Constants();
        // Setup Debug
        new Debug();
    }

}
?>