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
	 * Check if the token is valid
	 * @param string $secret
	 * @param string $token
	 * @param int $deltaTime
	 */
	public function validateToken( $secret, $token, $timeSlice = 30 ) {
	
		$timeSpan = floor(time() / $timeSlice);
		$tokenSize = strlen($token);
	
		for ($i = -$deltaTime; $i <= $deltaTime; $i++) {

			//If token generated in the timespan is equal witht he one given
			if ( $this->getToken($secret, $tokenSize, $timeSpan + $i, $timeSlice) == $token ) {
				//everything is OK
				return true;
			}
		}
	
		return false;
	}
	
	/**
	 * Gets a token for a given point in time
	 */
	private function getToken( $secret, $tokenSize = 6, $timeSpan = null, $timeSlice = 30 ) {
	
		if ($timeSpan === null) {
			$timeSpan = floor(time() / $timeSlice);
		}
	
		$secretkey = Base32::base32Decode($secret);
	
		// Binary voodoo
		$time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSpan);
		$hm = hash_hmac('SHA1', $time, $secretkey, true);
		$offset = ord(substr($hm, -1)) & 0x0F;
		$hashpart = substr($hm, $offset, 4);
		$value = unpack('N', $hashpart);
		$value = $value[1];
		$value = $value & 0x7FFFFFFF;
	
		//and voila
		$modulo = pow(10, $tokenSize);
		return str_pad($value % $modulo, $tokenSize, '0', STR_PAD_LEFT);
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