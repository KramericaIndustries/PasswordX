<?php

class ZepasswordStartingPointPackage extends StartingPointPackage {

	protected $pkgHandle = 'zepassword';
	
	public function getPackageName() {
		return t('Password System');
	}
	
	public function getPackageDescription() {
		return t('Password System Application');
	}
	
	/**
	 * Install a custom list of attributes
	 */
	public function install_attributes() {
		$ci = new ContentImporter();
		$ci->importContentFile(DIR_BASE. '/config/install/base/attributes.xml');
	}
	
	/**
	 * Overwrite the default confs. Couse we live the though life
	 */
	public function install_config() {
		
		//Import the predefines
		$ci = new ContentImporter();
		$ci->importContentFile(DIR_BASE_CORE. '/config/install/base/config.xml');
		
		//and manually save config for authy
		Config::save('AUTHY_API_KEY', AUTHY_API_KEY);
		Config::save('AUTHY_TYPE', 2); // 2 factor auth
		Config::save('AUTHY_SMS_TOKENS', 2); //sms token for all
		Config::save('AUTHY_SERVER_PRODUCTION', 1); //do not use sandbox servers
	}
	
}