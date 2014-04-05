<?php
/**
 * 
 * @author stefan
 * Sets up Concrete5 to be ready for used as password system
 */
class ZepasswordStartingPointPackage extends StartingPointPackage {

	//package handle
	protected $pkgHandle = 'zepassword';
	
	/**
	 * Package name
	 */
	public function getPackageName() {
		return t('Password System');
	}
	
	/**
	 * Package Description, required by C5
	 */
	public function getPackageDescription() {
		return t('Password System Application');
	}
	
	/**
	 * Override the parent contructor in order to add additional steps to the install process
	 */
	public function __construct() {
		
		//firstm let the parent do its stuff
		parent::__construct();
		
		//Inject a new routine in the queue
		$routine_count = sizeof($this->routines);
		$this->routines[ $routine_count ] = $this->routines[ $routine_count - 1 ];
		$this->routines[ $routine_count - 1 ] = new StartingPointInstallRoutine('admin_setup', 90, t('Setting up admin configurations.'));
		
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

	/**
	 * Sets up admin name and authy ID
	 */
	public function admin_setup() {
		
		//grab admin user
		$u = new User();
		$ui = UserInfo::getByID( $u->getUserID() );
		
		//set up the user attributes
		$ui->setAttribute( 'real_name', INSTALL_USER_NAME );
		$ui->setAttribute( 'phone_number', INSTALL_USER_PHONE );
		$ui->setAttribute( 'phone_country_code', INSTALL_USER_COUNTRY_CODE);
		
		//We dont want to see the newsflow, do we?
		$u->saveConfig('NEWSFLOW_LAST_VIEWED', time());
		
		//trigger update event in order to get the authy id
		Loader::library("event_handler");
		$event_lib = new EventHandler();
		$event_lib->user_updated($ui);
		
	}
	
}