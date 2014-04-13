<?php
defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Main crypto class that holds all the brainz
 */
class CryptoHelper {
	
	protected $EOT = '__EOT';	//string to appended to all text in order to avoid unknown endings
	protected $hmac_algo = 'ripemd160'; //algorithm used for MAC
	
	
	/**
	 * Generates random bytes securely
	 * @param int $length
	 */
	private function generateRandomBytes( $length ) {

		//Do we have access to openssl?
		if( function_exists('openssl_random_pseudo_bytes') ) {
				
			$rand = openssl_random_pseudo_bytes($length, $cstrong);
			
			if($cstrong) {
				return $rand;
			}
		}
		
		//If openssl is not available, try to fish out bytes from /dev/urandom 
		$rand = '';
		if( @is_readable('/dev/urandom') && ($fh = @fopen('/dev/urandom', 'rb')) ) {
			$rand = fread($fh, $length);
			fclose($fh);
		}

		//last fallback, in case we do not have access to /dev/urandom
		if (strlen($rand) < $length) {
			
			$rand = '';
			
			//Create a random state
			$random_state = microtime();
			if (function_exists('getmypid')) {
				$random_state .= getmypid();
			}
			
			//find some pseudoradom bytes
			for ($i = 0; $i < $length; $i += 16) {
				$random_state = md5(microtime() . $random_state);
				$rand .= pack('H*', md5($random_state));
			}
			
			$rand = substr($rand, 0, $length);
		}

		//return
		return $rand;
	}
	
	/**
	 * Generates a secure random string
	 * @param int $length
	 * @param bool $base64
	 */
	public function generateRandomString( $length = 16, $base64 = false ) {
		
		//compute how many bytes we will need to generate
		$bytes = $base64 ? $length : ceil( $length/2 ); 
		
		$rand = $this->generateRandomBytes( $bytes );
		
		//convert it to hex or base64
		$converted_rand = $base64 ? base64_encode($rand) : bin2hex($rand); 
		
		//trim the excess
		return substr( $converted_rand, 0, $length );
	}
	
	/**
	 * Encrypts a given text with a provoded key
	 */
	public function encrypt( $plaintext, $key ) {
		
		//append end of text delimiter
		$plaintext .= $this->EOT;
		
		$encryption_key = hash('sha256',$key,TRUE);
		
		// create a random IV to use with CBC encoding
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
    	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		
		$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $encryption_key, $plaintext, MCRYPT_MODE_CBC, $iv);

	    // prepend the IV for it to be available for decryption
	    $ciphertext = $iv . $ciphertext;
    
	    // encode the resulting cipher text so it can be represented by a string
	    $ciphertext_base64 = base64_encode($ciphertext);
		
		//autenticated encryption: encrypt-then-tag 
		$hmac = hash_hmac($this->hmac_algo, $ciphertext_base64, $key);
		
		//return
		return $ciphertext_base64 . ':' . $hmac;
	}
	
	/**
	 * Return Mater Encryption key
	 */
	public function decrypt( $encrypted_text, $key ) {
		
		//First, verify the HMAC and that the key is actually correct
		list( $ciphertext_base64, $hmac  ) = explode(':',$encrypted_text);
	
		$thisHmac = hash_hmac($this->hmac_algo, $ciphertext_base64, $key);
	
		if( $thisHmac != $hmac ) {
			return false;
		}

		//now that we insured that we are using the correct key, decrypt the text
		$encryption_key = hash('sha256',$key,TRUE);
		
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
		
		//deduce the encrypted message and the iv
		$ciphertext_dec = base64_decode($ciphertext_base64);
		$iv_dec = substr($ciphertext_dec, 0, $iv_size);
		$ciphertext_dec = substr($ciphertext_dec, $iv_size);
		
		//actually decript the text
		$plaintext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $encryption_key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
		
		//remove the end padding
		$original_text = explode($this->EOT, $plaintext);
		
		return $original_text[0];
	}
	
	/**
	 * Generates User Encryption Key.
	 * This is a password based hash, unique to each user
	 * that will encrypt the Master Key
	 * @param string $user_password
	 * @param string|null $UEK_SALT
	 * @return string
	 */
	public function computeUEK( $user_password, $UEK_SALT = null ) {
		
		//we need salt
		if( !defined('UEK_SALT') && empty($UEK_SALT) ) {
			throw new Exception('Error generating the UEK: no salt defined');
		}
		
		$salt = defined('UEK_SALT') ? UEK_SALT :  $UEK_SALT;
		
		$algorithms = array('sha512', 'ripemd320', 'whirlpool');
		$iterations = 2;
		
		for( $i=0; $i<$iterations; $i++ ) {
			
			$tempHash = '';
			$toHash = $user_password . $salt;
			
			foreach($algorithms as $algo) {
            	$tempHash .= hash($algo, $toHash);
           	}
			
			// Reset Hash length
            $toHash = hash($algo, $tempHash);
		}
		
		return $toHash;
	}
	
}
