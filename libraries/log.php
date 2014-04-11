<?php
defined('C5_EXECUTE') or die("Access Denied.");
/**
 * A library for dealing with searchable logs.
 * (c) 2014 PasswordX
 * Apache v2 License
 */
class Log extends Concrete5_Library_Log {
	
	/**
	 * Searches the logs for a user's last login
	 */
	public function getLastLogin() {
			
		$db = Loader::db();		
		global $u;

		//query DB
		$v = array( 'auth', $u->getUserId() );
		$r = $db->GetOne('select logID from Logs where logType = ? and logUserID = ? order by timestamp desc, logID desc', $v); 
			
		//return
		return LogEntry::getByID($r);
	}
	
}


class LogEntry extends Concrete5_Library_LogEntry {}