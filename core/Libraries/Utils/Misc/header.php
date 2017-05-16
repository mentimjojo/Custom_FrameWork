<?php
class Header {

    /**
     * Redirect to page
     * @param string $url
     * @param int $delay
     * @param bool $end
     */
    public static final function redirect(string $url, int $delay, bool $end = false){
        // Run ob
        ob_start();
        // Redirect
        header("refresh:" . $delay . ";url=" . $url);
        // Check if need to die
        if($end)
            die();
    }

}
?>