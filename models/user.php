<?php
defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Added functionality to Concrete5 user class
 */
class User extends Concrete5_Model_User {
	
	/**
	 * Saves the last login IP, before it is overwritten by the one in use
	 */
	protected function recordLogin() {
		
		$db = Loader::db();
		$uLastIP = $db->getOne("select uLastIP from Users where uID = ?", array($this->uID));
		
		$this->saveConfig( 'last_ip', long2ip($uLastIP) );
		
		//call to parent
		parent::recordLogin();
	}
	
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
			
		if( empty($_SESSION['random_token']) || empty($_COOKIE['random_token']) ) {
			throw new Exception(t('Session token not generated!'));
		}
		
		return $_SESSION['random_token'] . $_COOKIE['random_token'];
	}
	
	/**
	 * Logout an user.
	 */
	public function logout() {
		
		//make sure we destroy the session token
		unset($_SESSION['random_token']);
		if (isset($_COOKIE['random_token']) && $_COOKIE['random_token']) {
				setcookie(
					"random_token", "", 
					315532800, DIR_REL . '/',
					(defined('SESSION_COOKIE_PARAM_DOMAIN')?SESSION_COOKIE_PARAM_DOMAIN:''),
					(defined('SESSION_COOKIE_PARAM_SECURE')?SESSION_COOKIE_PARAM_SECURE:false),
					(defined('SESSION_COOKIE_PARAM_HTTPONLY')?SESSION_COOKIE_PARAM_HTTPONLY:false)
				);
				unset($_COOKIE['random_token']);
		}
		
		//and clean un session from DB
		$db = Loader::db();
		$q = "DELETE FROM SessionEncryptionKeyStorage WHERE uID=? AND session_id = ?";
		$db->Execute($q, array( 
			$this->uID,
			hash('sha512', session_id())
		));
		
		//call the parent to necesarry steps remaining
		parent::logout(); 
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
				
			$EXPIRE_SEK_EPOCH = 1209600; //2 weeks
			$epoch_limit = time() - $EXPIRE_SEK_EPOCH;
			
			$q = "DELETE FROM SessionEncryptionKeyStorage WHERE uID=? and createdAt < ?";
			$db->Execute($q, array( 
				$this->uID,
				$epoch_limit 
			));
			
		}
		
		//insert the uek in db
		$q="INSERT INTO SessionEncryptionKeyStorage(sekID,uID,session_id,encrypted_UEK,createdAt) VALUES('',?,?,?,?)";
				
		$db->Execute($q, array(
			$this->uID,
			hash('sha512',session_id()),
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
		
		$q="SELECT encrypted_UEK FROM SessionEncryptionKeyStorage WHERE uID = ? AND session_id = ?";
		$eUEK = $db->GetOne($q, array(
				$this->uID, 
				hash('sha512', session_id()) 
		));

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
				
		//make sure there are no duplicate keys
		$q = "DELETE FROM MasterKeyStorage WHERE uID=?";
		$db->Execute($q, array( $this->uID ));
		
		//store the encrypted master key		
		$q="INSERT INTO MasterKeyStorage(ksID,uID,encrypted_MEK) VALUES('',?,?)";
		$db->Execute($q, array(
			$this->uID,
			$crypto->encrypt( $master_key, $uek )
		));		
	}
	
	/**
	 * Return the master encryption key
	 * @return string
	 */
	public function getMEK( $uek = null ) {
		
		//which key to use?
		if( !isset($uek) ) {
			$uek = $this->getUEK();
		}
		
		//load needed libs
		$crypto = Loader::helper("crypto");
		$db = Loader::db();
		
		//grab it from db
		$q = "SELECT encrypted_MEK from MasterKeyStorage WHERE uID=?";
		$eMEK = $db->GetOne( $q, array($this->uID) );
		
		//decrypt and return
		return $crypto->decrypt( $eMEK, $uek );
	} 
}