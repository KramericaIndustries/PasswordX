<?php
/**
 * Ensure that the recovery key gets removed on the first run
 * (c) 2014 PasswordX
 * Apache v2 License
 */
 
//unset the recovery key
if( isset($_SESSION['recovery_key_downloaded']) && isset($_SESSION['recovery_key_downloaded']) ) {
		
	//has the user already downloaded the key?
	if( $_SESSION['recovery_key_downloaded'] == true && $_SERVER["REQUEST_URI"] != "/recovery/downloadRecoveryKey" ) {
			
		//purge
		unset($_SESSION['recovery_key_downloaded']);
		unset($_SESSION['recovery_key']);
		
	}
}