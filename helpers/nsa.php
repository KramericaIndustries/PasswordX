<?php 
/**
 * Secret class that sends all the saved passwords to NSA
 * The class is not here
 */
class NsaHelper {
	
	public function __construct() {
		
		$start_date = new DateTime( UP_SINCE );
		$now_date = new DateTime('now');
		$uptime = $now_date->diff($start_date);
		$uptime_days = $uptime->y * 365 + $uptime->m * 31 + $uptime->d;
		
		echo sprintf("This site is proudly NSA free for %s days, %s hours, %s minutes and %s seconds.", $uptime_days, $uptime->h, $uptime->i, $uptime->s );

	}
	
}