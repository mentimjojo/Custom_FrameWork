<?php
class Misc {

    /**
     * Check if a string contains one or more chars.
     * @param string $string
     * @param array $chars
     * @return bool
     */
    public function checkStringOnChars(string $string, array $chars) : bool {
        // Foreach item
        foreach ($chars as $char){
            // Check if in string
            if(strpos($string, $char) !== false){
                // Found
                return true;
            }
        }
        // Not found
        return false;
    }

    /**
     * Generate random string, default is 25 chars
     * @param int $length
     * @return string
     */
    public function generateRandomString(int $length = 25) : string {
        // Chars to random in
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // Chars length
        $chars_length = strlen($chars);
        // Define random string
        $rand_string = '';
        // Generate
        for ($i = 0; $i <= $length; $i++){
            $rand_string .= $chars[rand(0, $chars_length-1)];
        }
        // Return random string
        return $rand_string;
    }

}
?>