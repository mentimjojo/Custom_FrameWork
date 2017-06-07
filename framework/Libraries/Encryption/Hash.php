<?php

/**
 * Class Hash
 * Working with Password_hash method
 * @Author T.Nijborg
 * @Version 0.1
 */
class Hash
{

    /**
     * Create hash
     * @param string $plain the text to be hashed
     * @param int $cost how heavy the encryption is.
     * @return string
     */
    public static function make(string $plain, int $cost = 6): string
    {
        // Check cost, can not be lower then 4 or higher then 15
        if($cost < 4 || $cost > 15){
            // Set cost back to 4
            $cost = 6;
            // Send warning
            ErrorHandler::warning(201, 'Cost can not be lower then 4 or higher then 15.');
        }
        // Options
        $options = array(
            'cost' => $cost
        );
        // Check if hash is empty
        $hashed = password_hash($plain, PASSWORD_BCRYPT, $options);
        // Return hashed
        return $hashed;
    }

    /**
     * Check if hash is the same
     * @param string $plain
     * @param string $hashed
     * @return bool
     */
    public static function verify(string $plain, string $hashed): bool
    {
        // Check if hash is same
        if (password_verify($plain, $hashed)) {
            // Return true
            return true;
        } else {
            // Return false
            return false;
        }
    }

}

?>