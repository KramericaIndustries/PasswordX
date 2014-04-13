<?php
/**
 * Google Authneticator Helper
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied.");

class GoogleAuthenticatorHelper {

	/**
	 * Constructor
	 * Load base32 lib, we will need it no matter what
	 */
	public function __construct() {
		Loader::library("base32");
	}
	
	/**
	 * Returns 
	 * @param string $ga_secret
	 */
	public function getQrUrl( $ga_secret, $name='PasswordX' ) {
		$urlencoded = urlencode('otpauth://totp/' . $name . '?secret=' . $ga_secret);
        return 'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl='.$urlencoded.'';
	}
	
	/**
	 * Create new secret.
	 */
	public function createSecret( $secretLength = 16 ) {
		
		//Load base32 chars
		$validChars = Base32::getBase32Chars();
		
		//remove the padding character
		unset($validChars[32]);
	
		$secret = '';
		for ($i = 0; $i < $secretLength; $i++) {
			$secret .= $validChars[array_rand($validChars)];
		}
	
		return $secret;
	}

}