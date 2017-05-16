<?php
class Misc {

    /**
     * Check string on chars
     * @param string $string
     * @param array $array
     * @return bool
     */
    public function checkStringOnChars(string $string, array $array) : bool {
        // Foreach item
        foreach ($array as $item){
            // Check if in string
            if(strpos($string, $item) !== false){
                // Found
                return true;
            }
        }
        // Not found
        return false;
    }

}
?>