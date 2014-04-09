<?php 
/**
 * Secret class that sends all the saved passwords to the NSA
 * This class is not here *poof*
 */
class NsaHelper {

	/**
	 * Checks if a new version of the system is available
	 * @return bool
	 */
	public function newVersionAvailable() {

		$last_check_time = Config::get('upgrade_last_check');
		
		//set a ridiculous low last check time, it does not exist
		if( Config::get('upgrade_last_check') == null ) {
			Config::save('upgrade_last_check',100); 
			$last_check_time = 100;
		}

		// check for updates once a day
		if( (time() - (int)$last_check_time) >  86400 ) {
			
			//get the latest infos
			$latest_info = $this->getLatestVersionInfo();
			
			//most likely error while transfering
			//skip the next steps
			if( $latest_info == false ) {
				return false;
			}
			
			$latest_stable = $latest_info->latest_stable;
			$lastest_message = $latest_info->message;
			
			//cache the result
			Config::save('upgrade_lastest_stable', $latest_info->latest_stable);
			Config::save('upgrade_lastest_data', json_encode($latest_info));

			//save save time
			Config::save('upgrade_last_check', time());

		} else {	
			
			$latest_stable = Config::get('upgrade_lastest_stable');
		}

		if (version_compare(APP_VERSION, $latest_stable, '<')) {
			return json_decode( Config::get('upgrade_lastest_data') );;
		} else {
			return false;
		}

	}
	
	/**
	 * Downloads the latest data about latest release version
	 * @return array
	 */
	private function getLatestVersionInfo() {
		
		// Get cURL resource
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://www.passwordx.io/version.php'
		));
		
		// grab data
		$resp = curl_exec($curl);
		
		// Close request to clear up some resources
		curl_close($curl);
		
		return json_decode( $resp );
	}
	
	/**
	 * Checks if 2FA is disabled 
	 * @return bool
	 */
	public function disabledTwoFactor() {
		return Config::get('AUTHY_TYPE') == '0';
	}
	
	/**
	 * Checks if the transmission is secure (HTTPS)
	 * @return bool
	 */
	public function connectionUnsecured() {
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
			return false; //connection is secured
		} else {
			if( (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ) {
				return false; //connection is secured, via CloudFlare
			} else {
				return true; //connection is UNSECURED
			}
		}
	}
	
	/**
	 * Ester egg. Displays a "funny" message
	 */
	public function easter_egg() {
		
		$start_date = new DateTime( UP_SINCE );
		$now_date = new DateTime('now');
		$uptime = $now_date->diff($start_date);
		$uptime_days = $uptime->y * 365 + $uptime->m * 31 + $uptime->d;
		
		echo sprintf("This site has (probably) been NSA free for %s days, %s hours, %s minutes and %s seconds.", $uptime_days, $uptime->h, $uptime->i, $uptime->s );

	}
	
}