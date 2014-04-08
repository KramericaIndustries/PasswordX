<?php
defined('C5_EXECUTE') or die("Access Denied.");

class UserInfo extends Concrete5_Model_UserInfo {
	
		/**
		 * When the user is changing the password, change also the master key hash
		 */
		public function changePassword( $newPassword ) {
				
			//Add some login here
				 
			parent::changePassword();
		}
}