<?php
/**
 * Recovery
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied.");

class RecoveryController extends Controller {

	/**
     * On start. It's like a constructor, only cooler
     */
    public function on_start() {
        	
		//who knows what secrets parent holds for us?
        parent::on_start(); 
        
		//Test for recovery files to exist
        $mek_filename = Config::get('RECOVERY_MASTER_KEY');
		$this->set("recoveryTest", file_exists( DIR_BASE . '/config/recovery/recovery_key.rsa' ));
        $this->set("masterTest", file_exists( DIR_BASE . '/config/recovery/' . $mek_filename ));
		
        //no sidebar, please
        $this->set("nosidebar",true);
	} 

	/**
	 * Reset the password task
	 */	
	public function reset_passwd() {
		
		$token = Loader::helper('validation/token');
		
		$mek_filename = Config::get('RECOVERY_MASTER_KEY');
		
		//validate the security token
		if (!$token->validate('password_reset')) {
			throw new Exception('Invalid security token!');
		}
		
		//check that we have the needed files
		if( !file_exists( DIR_BASE . '/config/recovery/' . $mek_filename ) ) {
			throw new Exception('Master key not found!');
		}
		
		if( !file_exists( DIR_BASE . '/config/recovery/recovery_key.rsa' ) ) {
			throw new Exception('Recovery key not found!');
		}
		
		//Load needed library
		Loader::library( "3rdparty/phpseclib/Math/BigInteger" );
		Loader::library( "3rdparty/phpseclib/Crypt/RSA" );
		Loader::library( "3rdparty/phpseclib/Crypt/AES" );
		Loader::library( "3rdparty/phpseclib/Crypt/TripleDES" );
		Loader::library( "3rdparty/phpseclib/Crypt/Random" );
		$crypto = Loader::helper("crypto");
		
		$rsa = new Crypt_RSA();
		
		//read private key
		$filename = DIR_BASE . '/config/recovery/recovery_key.rsa';
		$handle = fopen($filename, "r");
		$private_key = fread($handle, filesize($filename));
		fclose($handle);
		
		//read encrypted master key
		$filename = DIR_BASE . '/config/recovery/' . $mek_filename;
		$handle = fopen($filename, "r");
		$eMEK = fread($handle, filesize($filename));
		fclose($handle);
		
		//decrypt the master key
		$rsa->loadKey($private_key);
		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		
		$dMEK = $rsa->decrypt($eMEK); 
		
		if( !$dMEK ) {
			throw new Exception("Invalid recovery key!");
		}
				
		//Login Admin User programatically
		$admin_user = User::loginByUserID(1);
		//$admin_user = User::getByUserID(1);
		$admin_user_ui = UserInfo::getByID(1);
				
		//plant the random session token
		$session_token = $admin_user->plantSessionToken();
		
		//generate a new password
		$new_passwd = $crypto->generateRandomString(32);
		
		//compute the new UEK and store it in DB
		$new_admin_uek = $crypto->computeUEK($new_passwd);
		
		//save the uek for this session
		$admin_user->saveSessionUEK( $new_admin_uek );
		
		//save the MEK encrypted with the new key for user
		$admin_user->saveMECforUser( $dMEK, $new_admin_uek );
		
		//Change the admin's password
		$admin_user_ui->changePassword( $new_passwd );
		
		//and remove the admin recovery key
		unlink( DIR_BASE . '/config/recovery/recovery_key.rsa' );
		
		//Log the event
		Log::addEntry('Admin password reseted from ' . $_SERVER["REMOTE_ADDR"],'passwd_reset');
		
		//and redirect to the profile page
		$this->redirect('/dashboard/users/search?uID=1&task=edit');
		
		exit(); //no chrome
	}
	
	/**
	 * Forces the download of the recovery key
	 */
	public function downloadRecoveryKey() {
		
		//Recovery key is set in session
		if( (isset($_SESSION['recovery_key'])) && (isset($_SESSION['recovery_key_downloaded'])) ) {
			
			//mark the fact that the file has been downloaded
			$_SESSION['recovery_key_downloaded'] = true;
			
			$filename = 'recovery_key.rsa';
			
			//set the header for forcing a donload
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $filename );
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			//header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			
			//output the content
			echo $_SESSION['recovery_key'];
			
			//and exit
			exit();
			
		} else {
				
			//one should not find itself here
			die("Access Denied");
		}
		
		//no chrome, please
		exit();	
	}
	
}