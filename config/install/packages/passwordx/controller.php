<?php
 /**
 * Sets up Concrete5 to be ready for used as password system
 * (c) 2014 PasswordX
 * Apache v2 License
 */
class PasswordxStartingPointPackage extends StartingPointPackage {

	//package handle
	protected $pkgHandle = 'passwordx';
	
	/**
	 * Package name
	 */
	public function getPackageName() {
		return t('PasswordX');
	}
	
	/**
	 * Package Description, required by C5
	 */
	public function getPackageDescription() {
		return t('PasswordX Core Application');
	}
	
	/**
	 * Override the parent contructor in order to add additional steps to the install process
	 */
	public function __construct() {

		Loader::library('content/importer');
		
		//set up rutines
		$this->routines = array(
				new StartingPointInstallRoutine('make_directories', 5, t('Starting installation and creating directories.')),
				new StartingPointInstallRoutine('install_database', 10, t('Creating database tables.')),
				new StartingPointInstallRoutine('add_users', 15, t('Adding admin user.')),
				new StartingPointInstallRoutine('install_permissions', 20, t('Installing permissions & workflow.')),
				new StartingPointInstallRoutine('add_home_page', 23, t('Creating home page.')),
				new StartingPointInstallRoutine('install_attributes', 25, t('Installing attributes.')),
				new StartingPointInstallRoutine('install_blocktypes', 30, t('Adding block types.')),
				new StartingPointInstallRoutine('install_themes', 35, t('Adding themes.')),
				new StartingPointInstallRoutine('install_jobs', 38, t('Installing automated jobs.')),
				new StartingPointInstallRoutine('install_dashboard', 40, t('Installing dashboard.')),
				new StartingPointInstallRoutine('install_required_single_pages', 50, t('Installing login and registration pages.')),
				new StartingPointInstallRoutine('install_config', 55, t('Configuring site.')),
				new StartingPointInstallRoutine('import_files', 58, t('Importing files.')),
				new StartingPointInstallRoutine('install_content', 65, t('Adding pages and content.')),
				new StartingPointInstallRoutine('set_site_permissions', 70, t('Setting up site permissions.')),
				new StartingPointInstallRoutine('precache', 80, t('Prefetching information.')),
				new StartingPointInstallRoutine('admin_setup', 85, t('Setting up admin configurations.')),
				new StartingPointInstallRoutine('configure_permissions', 87, t('Setting up admin configurations.')),
				new StartingPointInstallRoutine('encryption_setup', 92, t('Setting up encryption system.')),
				new StartingPointInstallRoutine('finish', 95, t('Finishing.'))
		);
	}
	
	/**
	 * Installing database scheme
	 */
	public function install_database() {
		$db = Loader::db();			
		$installDirectory = DIR_BASE . '/config';
		try {
			Database::ensureEncoding();
			Package::installDB($installDirectory . '/db.xml');
		} catch (Exception $e) { 
			throw new Exception(t('Unable to install database: %s', $db->ErrorMsg()));
		}
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
		$ci->importContentFile(DIR_BASE. '/config/install/base/config.xml');
		
		//and manually save config for authy
		
		//do not set tfa, unless the user enter authy setup
		$auth_type = TWO_FACTOR_AUTH_METHOD == 'authy' ? 2 : 0; 
		
		Config::save('AUTHY_API_KEY', AUTHY_API_KEY);
		Config::save('AUTHY_TYPE', $auth_type); // 2 factor auth
		Config::save('AUTHY_SMS_TOKENS', 2); //sms token for all
		Config::save('AUTHY_SERVER_PRODUCTION', 1); //do not use sandbox servers
	}
	
	/**
	 * Remove blocktype we do not need at install
	 */
	public function install_blocktypes() {
		
		//Import Block types
		$ci = new ContentImporter();
		$ci->importContentFile(DIR_BASE. '/config/install/base/blocktypes.xml');
		
	}
	
	/**
	 * Remove C5 demo themes
	 */
	public function install_themes() {
		$ci = new ContentImporter();
		$ci->importContentFile(DIR_BASE. '/config/install/base/themes.xml');
	}
	
	/**
	 * Do not install all the dashboard
	 */
	public function install_dashboard() {
		$ci = new ContentImporter();
		$ci->importContentFile(DIR_BASE. '/config/install/base/dashboard.xml');
	}
	
	/**
	 * Make sure we install a few pages as system pages
	 */
	public function install_required_single_pages() {
		Loader::model('single_page');
		$ci = new ContentImporter();
		$ci->importContentFile(DIR_BASE . '/config/install/base/login_registration.xml');
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
		
		//We dont want to see the newsflow, do we?
		$u->saveConfig('NEWSFLOW_LAST_VIEWED', time());
		
		//set up authy, if user selected so
		if( TWO_FACTOR_AUTH_METHOD == 'authy' ) {
			
			$ui->setAttribute( 'phone_number', INSTALL_USER_PHONE );
			$ui->setAttribute( 'phone_country_code', INSTALL_USER_COUNTRY_CODE);
			
			Loader::library("event_handler");
			$event_lib = new EventHandler();
			$event_lib->user_updated($ui);
		}
	}
		
	/**
	 * Here is the place where we set up defaukt permissions for the site tree
	 */
	public function configure_permissions() {
		
		//get all groups
		$guest_group = Group::getByID(GUEST_GROUP_ID);
		$registered_group = Group::getByID(REGISTERED_GROUP_ID);
		$admin_group = Group::getByID(ADMIN_GROUP_ID);
		
		//all pages should be visible only to logged in persons
		$home = Page::getByID(1, "RECENT");
		$home->clearPagePermissions();
		$home->assignPermissions($registered_group, array('view_page'));
		$home->assignPermissions($admin_group, array('view_page_versions', 'view_page_in_sitemap', 'preview_page_as_user', 'edit_page_properties', 'edit_page_contents', 'edit_page_speed_settings', 'edit_page_theme', 'edit_page_type', 'edit_page_permissions', 'delete_page', 'delete_page_versions', 'approve_page_versions', 'add_subpage', 'move_or_copy_page', 'schedule_page_contents_guest_access'));
		
		
		//everything under My Passwords should be visible to registered users
		$my_pass = Page::getByPath('/my-passwords');
		$my_pass->clearPagePermissions();
		$my_pass->assignPermissions($registered_group, array('view_page','view_page_in_sitemap','edit_page_properties','edit_page_contents','approve_page_versions', 'add_subpage'));
		$my_pass->assignPermissions($admin_group, array('view_page_versions', 'view_page_in_sitemap', 'preview_page_as_user', 'edit_page_properties', 'edit_page_contents', 'edit_page_speed_settings', 'edit_page_theme', 'edit_page_type', 'edit_page_permissions', 'delete_page', 'delete_page_versions', 'approve_page_versions', 'add_subpage', 'move_or_copy_page', 'schedule_page_contents_guest_access'));
		//Create a new site area and allow manual change
		$my_pass->setPermissionsToManualOverride();
		$my_pass->setPermissionsInheritanceToOverride();
		
	}


	/**
	 * Set up MEK, UEK and other TLA
	 */
	public function encryption_setup() {
		
		//store MEK for admin
		$crypto = Loader::helper("crypto");
		
		//retrieve admin UEK
		$admin_uek = $crypto->decrypt( ADMIN_eUEK, $_SESSION['session_randomness'] );
		unset($_SESSION['session_randomness']);
		
		//generate the master encryption key
		$MEK = $crypto->generateRandomString(1024);
		
		//grab ze user
		$admin = new User();
		
		//save the masterkkey
		$admin->saveMECforUser( $MEK, $admin_uek );
		
		//and save the session tokens and uek
		$admin->plantSessionToken();
		$admin->saveSessionUEK( $admin_uek );
		
		//
		// Create the backup key
		//	
		Loader::library( "3rdparty/phpseclib/Math/BigInteger" );
		Loader::library( "3rdparty/phpseclib/Crypt/RSA" );
		Loader::library( "3rdparty/phpseclib/Crypt/AES" );
		Loader::library( "3rdparty/phpseclib/Crypt/TripleDES" );
		Loader::library( "3rdparty/phpseclib/Crypt/Random" );
		
		$rsa = new Crypt_RSA();
		$keys = $rsa->createKey(4096);
		
		//save the key for download
		$_SESSION['recovery_key'] = $keys["privatekey"];
		$_SESSION['recovery_key_downloaded'] = false;
		
		//
		// Now encrypt the MEK and save it
		//
		$rsa->loadKey($keys["publickey"]);
		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		$cipherMEK = $rsa->encrypt($MEK);
		
		$cipher_MEK_file = DIR_BASE . '/config/recovery/master_key';
		
		if (!$handle = fopen($cipher_MEK_file, 'w')) {
		     throw new Exception("Error opening master key file for write");
		     exit;
		}
		
		// Write $somecontent to our opened file.
		if (fwrite($handle, $cipherMEK) === FALSE) {
		    throw new Exception("Error writing the master key file!");
		    exit;
		}
		
		fclose($handle);
				
		//clean up
		unset($rsa);
		unset($MEK);
		unset($cipherMEK);
	}
	
	/**
	 * Make final changes before finishing
	 */
	public function finish() {

		$db = Loader::db();

		//bubble up our blocks to the top
		$q="UPDATE BlockTypes SET btDisplayOrder=? WHERE btHandle=?";
		
		//bubble_up
		$db->Execute($q, array(1,'encrypted_generic_pass'));
		$db->Execute($q, array(1,'encrypted_vhost'));
		
		//push down
		$db->Execute($q, array(2,'content'));
		
		parent::finish(); //let concrete finish the install
	}
	
}