<?php
class Config {

    /**
     * Config constructor.
     */
    public function __construct()
    {
        // Set timezone to Netherlands (Because developer lives there)
        settings::setTimezone();
        // Setup Constants
        new Constants();
        // Setup Debug
        new Debug();
    }

}
?>