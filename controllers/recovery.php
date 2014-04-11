<?php
defined('C5_EXECUTE') or die("Access Denied.");

class RecoveryController extends Controller {

	public function view() {
		echo "view task";
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