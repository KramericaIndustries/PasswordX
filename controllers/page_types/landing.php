<?php

class LandingPageTypeController extends Controller {
	
	/**
	 * Remove the admin key on first run
	 */
	public function on_start(){
			
		parent::on_start(); //Call back into the BaseController
		
		if( file_exists(DIR_CONFIG_SITE . '/recovery/recovery_key.rsa') ) {
			if( unlink(DIR_CONFIG_SITE . '/recovery/recovery_key.rsa') === false ) {
				throw new Exception("Could not remove recovery key. Please do it manually!");
			}
		}
		
	}
	
} 
