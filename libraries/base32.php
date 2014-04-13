<?php
/**
 * Base32 Library
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied.");

class Base32 {
	
    /**
     * Static list of the 32 chars
     */
    public static function getBase32Chars() {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
            'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
            'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31
            '='  // padding char
        );
    }
    	
}

