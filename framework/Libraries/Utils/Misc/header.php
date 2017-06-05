<?php

class Header
{

    /**
     * Redirect to a url.
     * @param string $url
     * @param int $delay
     * @param bool $die
     */
    public final function redirect(string $url, int $delay = 0, bool $die = false)
    {
        // Run ob
        ob_start();
        // Redirect
        header("refresh:" . $delay . ";url=" . $url);
        // Check if need to die
        if ($die) {
            // Die website, so website redirects instant
            die();
        }
    }

}

?>