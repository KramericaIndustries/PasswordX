<?php 
/**
 * Secret class that sends all the saved passwords to NSA
 * The class is not here
 */
class NsaHelper {
		
	
	/**
	 * Checks if 2FA is disabled 
	 * @return bool
	 */
	public function disabledTwoFactor() {
		return Config::get('AUTHY_TYPE') == '0';
	}
	
	/**
	 * Checks if the transmission is over HTTPS 
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
	 * Ester egg. Deplays a funny message
	 */
	public function easter_egg() {
		
		$start_date = new DateTime( UP_SINCE );
		$now_date = new DateTime('now');
		$uptime = $now_date->diff($start_date);
		$uptime_days = $uptime->y * 365 + $uptime->m * 31 + $uptime->d;
		
		echo sprintf("This site is proudly NSA free for %s days, %s hours, %s minutes and %s seconds.", $uptime_days, $uptime->h, $uptime->i, $uptime->s );

	}
	
}