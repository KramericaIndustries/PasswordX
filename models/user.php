<?php
defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Added functionality to Concrete5 user class
 */
class User extends Concrete5_Model_User {
	
	/**
	 * Plant a session seed in session and in cookie
	 * used for encryption uek
	 * @param int $size
	 */
	public function plantSessionToken( $size = 1024 ) {
			
		$crypto = Loader::helper("crypto");
		
		$session_saved_token = $crypto->generateRandomString($size/2,true);
		$cookie_saved_token = $crypto->generateRandomString($size/2,true);
		
		//and save them in cookie and in session
		$_SESSION['random_token'] = $session_saved_token;
		$_COOKIE['random_token'] = $cookie_saved_token; 
		setcookie('random_token',
				$cookie_saved_token, 
				time() + USER_FOREVER_COOKIE_LIFETIME, 
				DIR_REL . '/', 
				(defined('SESSION_COOKIE_PARAM_DOMAIN')?SESSION_COOKIE_PARAM_DOMAIN:''),
				(defined('SESSION_COOKIE_PARAM_SECURE')?SESSION_COOKIE_PARAM_SECURE:false),
				(defined('SESSION_COOKIE_PARAM_HTTPONLY')?SESSION_COOKIE_PARAM_HTTPONLY:false)
		);  
		
		return $session_saved_token . $cookie_saved_token;
	}
	
	/**
	 * Returns the session random token
	 * @return string
	 */
	public function getSessionToken() {
		return $_SESSION['random_token'] . $_COOKIE['random_token'];
	}
	
	/**
	 * Saved the uek encrypted with the session key in the database
	 * @param string $uek
	 * @param bool $garbage_collection 
	 */
	public function saveSessionUEK( $uek, $garbage_collection = true ) {

		//load needed libs
		$crypto = Loader::helper("crypto");
		$db = Loader::db();
		
		//garbage collection, remove old entries
		if( $garbage_collection ) {
			$q = "DELETE FROM SessionEncryptionKeyStorage WHERE uID=?";
			$db->Execute($q, array( $this->uID ));
		}
		
		//insert the uek in db
		$q="INSERT INTO SessionEncryptionKeyStorage(sekID,uID,encrypted_UEK,createdAt) VALUES('',?,?,?)";
				
		$db->Execute($q, array(
			$this->uID,
			$crypto->encrypt( $uek, $this->getSessionToken() ),
			time()
		));
	}

	/**
	 * Returns users UEK
	 * @return string
	 */
	public function getUEK() {
		
		//load needed libs
		$crypto = Loader::helper("crypto");
		$db = Loader::db();
		
		$q="SELECT encrypted_UEK FROM SessionEncryptionKeyStorage WHERE uID = ? ORDER BY sekID DESC";
		$eUEK = $db->GetOne($q, array($this->uID));

		return (isset( $eUEK ) ? $crypto->decrypt( $eUEK, $this->getSessionToken() ) : false ); 
	}
	
	/**
	 * Saved the MEC for the user
	 * @param string $master_key
	 * @param string|null $uek
	 */
	public function saveMECforUser( $master_key, $uek = null ) {
			
		//which key to use?
		if( !isset($uek) ) {
			$uek = $this->getUEK();
		}

		//load needed libs
		$crypto = Loader::helper("crypto");
		$db = Loader::db();
				
		$q="INSERT INTO MasterKeyStorage(ksID,uID,encrypted_MEK) VALUES('',?,?)";
		
		//store the encrypted master key
		$db->Execute($q, array(
			$this->uID,
			$crypto->encrypt( $master_key, $uek )
		));		
	}
	
}