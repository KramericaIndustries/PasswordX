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
				new StartingPointInstallRoutine('set_site_permissions', 80, t('Setting up site permissions.')),
				new StartingPointInstallRoutine('precache', 85, t('Prefetching information.')),
				new StartingPointInstallRoutine('admin_setup', 90, t('Setting up admin configurations.')),
				new StartingPointInstallRoutine('configure_permissions', 92, t('Setting up admin configurations.')),
				new StartingPointInstallRoutine('finish', 95, t('Finishing.'))
		);
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
		Config::save('AUTHY_API_KEY', AUTHY_API_KEY);
		Config::save('AUTHY_TYPE', 2); // 2 factor auth
		Config::save('AUTHY_SMS_TOKENS', 2); //sms token for all
		Config::save('AUTHY_SERVER_PRODUCTION', 1); //do not use sandbox servers
	}
	
	/**
	 * Remove blocktype we do not need at install
	 */
	public function install_blocktypes() {
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
	
	/**
	 * Here is the place where we set up defaukt permissions for the site tree
	 */
	public function configure_permissions() {
		
	}
	
}