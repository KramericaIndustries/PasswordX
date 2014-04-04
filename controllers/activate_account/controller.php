<?php
/*
* Completes user registration
* (C) Hammerti.me @ 2014
* SF
*/
defined('C5_EXECUTE') or die("Access Denied.");

class ActivateAccountController extends Controller {

	/**
	* View task. Verifies hash for activation
	*/
	public function view( $uHash='' ) {
		
		$db = Loader::db();
		$h = Loader::helper('validation/identifier');
		$e = Loader::helper('validation/error');
		
		//get the user associated with the hash
		$ui = UserInfo::getByValidationHash($uHash);
		
		if (is_object($ui)) {
		
			//we have a validation date
			$hashCreated = $db->GetOne("select uDateGenerated FROM UserValidationHashes where uHash=?", array($uHash));
			if ($hashCreated < (time()-(USER_CHANGE_PASSWORD_URL_LIFETIME))) {
				$h->deleteKey('UserValidationHashes','uHash',$uHash);
				throw new Exception( t('Key Expired. Please visit the forgot password page again to have a new key generated.') );
			} else {
			
				//if post, start processing
				if( $this->isPost() ) {
				
					if (strlen($_POST['uPassword']) && $_POST['uPasswordConfirm']!=$_POST['uPassword']) {
						throw new Exception("The 2 passwords dont match!");
					}
				
					//allow the key only one
					$h->deleteKey('UserValidationHashes','uHash',$uHash);
				
					//change the password on the user
					$ui->changePassword( $_POST['uPassword'] );
				
					
					//make sure the attributes are in the expected format
					$clean_phone_number = preg_replace("/[^0-9]/", "", $_POST['phone']);
					
					Loader::model('user_attributes');
					if( empty($_POST['country_code']) ) {
						$_POST['country_code'] = "Denmark +45";
					}
					list( $cntry, $cc ) = explode( ' ', $_POST['country_code'] );
					$cc = trim( $cc, '()');
					$dirty_cc = sprintf( "%s (%s)", $cc, $cntry );
					$full_country_code_id = SelectAttributeTypeOption::getByValue($dirty_cc, UserAttributeKey::getByHandle('phone_country_code'));

					//add phone number and CC as attribiutes
					$ui->setAttribute( 'phone_number', $clean_phone_number );
					$ui->setAttribute( 'phone_country_code', $full_country_code_id );
				
					//manually fire the user update event for obtaining the Authy ID
					Events::fire('on_user_update', $ui);
				
					$authy_id = $ui->getAttribute( 'authy_user_id');
					if( empty( $authy_id ) ) {
						throw new Exception("Error while creating the 2 factor authentication");
					}
				
					$this->redirect("/login");
				
				} else {
					$this->set('uHash',$uHash);
				}
			
			}
		
		} else {
			throw new Exception( t('Invalid Key. Please contact the administrator for another key!') );
		}
	}

}
?>
