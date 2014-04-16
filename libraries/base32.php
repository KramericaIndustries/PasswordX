<?php
/**
 * Base32 Library
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied.");

class Base32 {
	
	/**
	 * Decode base 32
	 */
	public static function base32Decode( $secret ) {
	
		if (empty($secret))
			return '';
	
		$base32chars = self::getBase32Chars();
		$base32charsFlipped = array_flip($base32chars);
	
		$paddingCharCount = substr_count($secret, $base32chars[32]);
		$allowedValues = array(6, 4, 3, 1, 0);
		if (!in_array($paddingCharCount, $allowedValues)) return false;
		for ($i = 0; $i < 4; $i++){
			if ($paddingCharCount == $allowedValues[$i] &&
			substr($secret, -($allowedValues[$i])) != str_repeat($base32chars[32], $allowedValues[$i])) return false;
		}
		$secret = str_replace('=','', $secret);
		$secret = str_split($secret);
		$binaryString = "";
	
		for ($i = 0; $i < count($secret); $i = $i+8) {
			$x = "";
			if (!in_array($secret[$i], $base32chars)) return false;
			for ($j = 0; $j < 8; $j++) {
				$x .= str_pad(base_convert(@$base32charsFlipped[@$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
			}
			$eightBits = str_split($x, 8);
			for ($z = 0; $z < count($eightBits); $z++) {
				$binaryString .= ( ($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48 ) ? $y:"";
			}
		}
		return $binaryString;
	}
	
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

