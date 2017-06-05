<?php

class Config
{

    /**
     * Config constructor.
     */
    public function __construct()
    {
        // Check startup complete
        if (!Constants::$startup_complete) {
            // Enable debug for now
            Settings::toggleDebug(true);
        }
        // Set timezone to Netherlands (Because developer lives there)
        settings::setTimezone();
        // Setup Constants
        new Constants();
        // Setup Debug
        new Debug();
    }

}

?>