<?php
defined('C5_EXECUTE') or die("Access Denied.");

/**
 * While the User object deals more with logging users in and relating them to core Concrete items, 
 * like Groups, the UserInfo object is made to grab auxiliary data about a user, including their user attributes. 
 * Additionally, the UserInfo object is the object responsible for adding/registering users in the system.
 */
class UserInfo extends Concrete5_Model_UserInfo {
	
		/**
		 * Add a new user
		 * @param array $data
		 */
		public static function add($data,$options=false) {
			
			//call to parent for main add
			$new_user_info = parent::add( $data, $options );
			
			//now we add MEK
			$crypto = Loader::helper("crypto");
			$new_user_uek = $crypto->computeUEK( $data['uPassword'] ); 
			
			//get the MEK, via the logged in admin/superuser
			$user = new User();
			$mek = $user->getMEK();
			
			//and save it for the user
			$new_user = $new_user_info->getUserObject(); 
			$new_user->saveMECforUser( $mek, $new_user_uek );
			
			//return what we got from parent
			return $new_user_info;
		}
	
		/**
		 * When the user is changing the password, change also the master key hash
		 */
		public function changePassword( $newPassword ) {
				
			//Add some login here
				 
			parent::changePassword();
		}
}