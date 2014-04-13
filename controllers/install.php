<?php
defined('C5_EXECUTE') or die("Access Denied.");

class InstallController extends Concrete5_Controller_Install {

	/** 
	 * Since the method that tests the requirement is private, it cannot
	 * be overriten in children class.
	 * Add a call to extented requirements call
	 */
	public function on_start() {
		parent::on_start();
		$this->setRequiredItemsExtended();
	}
	
	/**
	 * Write we need recovery dir to be writeble aswell
	 * @return boolean
	 */
	protected function testFileWritePermissions() {
		
		$e = Loader::helper('validation/error');

		if (!is_writable(DIR_CONFIG_SITE)) {
			$e->add(t('Your configuration directory config/ does not appear to be writable by the web server.'));
		}

		if (!is_writable(DIR_FILES_UPLOADED)) {
			$e->add(t('Your files directory files/ does not appear to be writable by the web server.'));
		}
		
		if (!is_writable(DIR_PACKAGES)) {
			$e->add(t('Your packages directory packages/ does not appear to be writable by the web server.'));
		}
		
		if (!is_writable(DIR_CONFIG_SITE . '/recovery')) {
			$e->add(t('Your recovery directory config/recovery/ does not appear to be writable by the web server.'));
		}
		
		$this->fileWriteErrors = $e;
		if ($this->fileWriteErrors->has()) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * If all the config steps are done, let's install this beatch
	 */
	protected function testAndRunInstall() {
		if (file_exists(DIR_CONFIG_SITE . '/site_install_user.php')) {
			require(DIR_CONFIG_SITE . '/site_install.php');
			@include(DIR_CONFIG_SITE . '/site_install_user.php');
			if (defined('ACTIVE_LOCALE') && Localization::activeLocale() !== ACTIVE_LOCALE) {
				Localization::changeLocale(ACTIVE_LOCALE);
			}
			$e = Loader::helper('validation/error');
			$e = $this->validateDatabase($e);
			
			if( TWO_FACTOR_AUTH_METHOD == 'authy' ) {
				$e = $this->validateAuthy($e);
			}
			
			if ($e->has()) {
				$this->set('error', $e);
			} else { 
				$this->addHeaderItem(Loader::helper('html')->css('jquery.ui.css'));
				$this->addHeaderItem(Loader::helper('html')->javascript('jquery.ui.js'));
				if (defined('INSTALL_STARTING_POINT') && INSTALL_STARTING_POINT) {
					$spl = Loader::startingPointPackage(INSTALL_STARTING_POINT);
				} else {
					$spl = Loader::startingPointPackage('standard');
				}
				$this->set('installPackage', $spl->getPackageHandle());
				$this->set('installRoutines', $spl->getInstallRoutines());
				$this->set('successMessage', t('Congratulations! PasswordX has been installed. You have been logged in as <b>%s</b> with the password you chose. It seems that everything went better than expected.', USER_SUPER));
			}
		}
	}
	
	/**
	 * When we reach setup, generate a google auth secret, just in case:)
	 */
	public function setup() {
		
		$ga = Loader::helper("google_authenticator");
		$ga_secret = $ga->createSecret();
		
		$this->set("ga_secret", $ga_secret);
		$this->set("qr_url", $ga->getQrUrl($ga_secret));
		
		parent::setup();
	}
	

	/**
	 * Check for additional requirements for the app to run
	 */
	protected function setRequiredItemsExtended() {

		//security related
		$this->set('hashTest', function_exists('hash'));
		$this->set('mcryptTest', function_exists('mcrypt_module_self_test'));
		$this->set('opensslTest', function_exists('openssl_random_pseudo_bytes'));
	}
	
	/**
	 * validate the authy api key provided
	 * @param $e
	 * @return $e
	 */
	protected function validateAuthy($e) {
		
		if( !function_exists("curl_version") ) {
			$e->add( t('Function curl_version() not found. Your system does not appear to have cURL available within PHP.') );
		} else {
			
			//Loader::helper does not accept params for constructor.
			//load the file manually
			require( DIR_BASE . "/helpers/authy.php" );
			$authy = new AuthyHelper(false); //do not read config
			
			$api_key = defined('AUTHY_API_KEY') ? AUTHY_API_KEY : $_POST['AUTHY_API_KEY'];
			
			if( !$authy->validAPIKey( $api_key ) ) {
				$e->add( "Authy Error: " . $authy->getLastError() );
			}
			
		}

		return $e;
	}
	
	/**
	 * Overwrite the Configure part of the script
	 */
	public function configure() {	
		try {

			$val = Loader::helper('validation/form');
			$val->setData($this->post());
			$val->addRequired("SITE", t("Please specify your site's name"));
			$val->addRequired("uName", t('You must specify a valid name'));
			$val->addRequiredEmail("uEmail", t('Please specify a valid email address'));
			$val->addRequired("DB_DATABASE", t('You must specify a valid database name'));
			$val->addRequired("DB_SERVER", t('You must specify a valid database server'));
			
			if( $_POST['TWO_FACTOR_AUTH_METHOD'] == "authy" ) {
				$val->addRequired("AUTHY_API_KEY", t('You must specify a Authy API Key'));
				$val->addRequired("authy-cellphone", t('You must specify a valid phone number'));
				$val->addRequired("countryCode", t('You must specify a valid country'));
			}

			//Unless the page was tampered with, this should not be triggered
			if( $_POST['TWO_FACTOR_AUTH_METHOD'] == 'google' ) {
				$val->addRequired("GA_SECRET", t('Invalid Google Auth secret'));
			}
			
			//send to view the values for GA, in case or error
			$ga = Loader::helper("google_authenticator");
			$this->set("ga_secret", $_POST['GA_SECRET']);
			$this->set("qr_url", $ga->getQrUrl($ga_secret));
			
			$password = $_POST['uPassword'];
			$passwordConfirm = $_POST['uPasswordConfirm'];

			$e = Loader::helper('validation/error');
			$uh = Loader::helper('concrete/user');
			$uh->validNewPassword($password, $e);
	
			if ($password) {
				if ($password != $passwordConfirm) {
					$e->add(t('The two passwords provided do not match.'));
				}
			}
			
			if(is_object($this->fileWriteErrors)) {
				$e = $this->fileWriteErrors;
			}
			
			$e = $this->validateDatabase($e);
			
			if( $_POST['TWO_FACTOR_AUTH_METHOD'] == "authy" ) {
				$e = $this->validateAuthy($e);	
			}
			
			//This is just a gimmick call, for C5 reasons
			$e = $this->validateSampleContent($e);
			
			if ($val->test() && (!$e->has())) {
				
				//Crypto Helper
				$crypto = Loader::helper("crypto"); 

				// write the config file
				$vh = Loader::helper('validation/identifier');
				$this->fp = @fopen(DIR_CONFIG_SITE . '/site_install.php', 'w+');
				$this->fpu = @fopen(DIR_CONFIG_SITE . '/site_install_user.php', 'w+');
				if ($this->fp) {
					$configuration = "<?php\n";
					$configuration .= "define('DB_SERVER', '" . addslashes($_POST['DB_SERVER']) . "');\n";
					$configuration .= "define('DB_USERNAME', '" . addslashes($_POST['DB_USERNAME']) . "');\n";
					$configuration .= "define('DB_PASSWORD', '" . addslashes($_POST['DB_PASSWORD']) . "');\n";
					$configuration .= "define('DB_DATABASE', '" . addslashes($_POST['DB_DATABASE']) . "');\n";
					
					//UEK_SALT
					$UEK_SALT = $crypto->generateRandomString(128);
					$configuration .= "define('UEK_SALT', '" . addslashes($UEK_SALT) . "');\n";
					
					if (isset($setPermissionsModel)) {
						$configuration .= "define('PERMISSIONS_MODEL', '" . addslashes($setPermissionsModel) . "');\n";
					}
					if (is_array($_POST['SITE_CONFIG'])) {
						foreach($_POST['SITE_CONFIG'] as $key => $value) {
							if( $value == "false" || $value == "true" ) {
								$configuration .= "define('" . $key . "', " . $value . ");\n";	
							} else {
								$configuration .= "define('" . $key . "', '" . $value . "');\n";
							}
						}
					}
					$res = fwrite($this->fp, $configuration);
					fclose($this->fp);
					chmod(DIR_CONFIG_SITE . '/site_install.php', 0700);
				} else {
					throw new Exception(t('Unable to open config/site.php for writing.'));
				}

				if ($this->fpu) {
					Loader::library('3rdparty/phpass/PasswordHash');
					$hasher = new PasswordHash(PASSWORD_HASH_COST_LOG2, PASSWORD_HASH_PORTABLE);
					$configuration = "<?php\n";
					$configuration .= "define('INSTALL_USER_NAME', '" . $_POST['uName'] . "');\n";
					$configuration .= "define('INSTALL_USER_EMAIL', '" . $_POST['uEmail'] . "');\n";
					$configuration .= "define('INSTALL_USER_PASSWORD_HASH', '" . $hasher->HashPassword($_POST['uPassword']) . "');\n";
					$configuration .= "define('INSTALL_STARTING_POINT', '" . $this->post('SAMPLE_CONTENT') . "');\n";
					$configuration .= "define('SITE', '" . addslashes($_POST['SITE']) . "');\n";
					$configuration .= "define('INSTALL_USER_NAME', '" . $_POST['uName'] . "');\n";
					
					//2FA related 
					$configuration .= "define('TWO_FACTOR_AUTH_METHOD', '" . $_POST['TWO_FACTOR_AUTH_METHOD'] . "');\n";
					
					$configuration .= "define('AUTHY_API_KEY', '" . addslashes($_POST['AUTHY_API_KEY']) . "');\n";
					$configuration .= "define('INSTALL_USER_PHONE', '" . addslashes($_POST['authy-cellphone']) . "');\n";
					$configuration .= "define('INSTALL_USER_COUNTRY_CODE', '" . addslashes($_POST['countryCode']) . "');\n";
					
					$configuration .= "define('GA_SECRET', '" . addslashes($_POST['GA_SECRET']) . "');\n";
					
					
					//compute the user encryption key
					$admin_uek = $crypto->computeUEK( $_POST['uPassword'], $UEK_SALT );
					
					//generate a session randomness and encrypt it
					//this is going to be used only for passing the admin_uek securely 
					//to the nest stage of the install
					$_SESSION['session_randomness'] = $crypto->generateRandomString(1024); 
					$eUEK = $crypto->encrypt( $admin_uek, $_SESSION['session_randomness'] );
					
					//save it in file
					$configuration .= "define('ADMIN_eUEK', '" . $eUEK . "');\n";
					
					if (defined('ACTIVE_LOCALE') && ACTIVE_LOCALE != '' && ACTIVE_LOCALE != 'en_US') {
						$configuration .= "define('ACTIVE_LOCALE', '" . ACTIVE_LOCALE . "');\n";
					}
					$res = fwrite($this->fpu, $configuration);
					fclose($this->fpu);
					chmod(DIR_CONFIG_SITE . '/site_install_user.php', 0700);
					if (PHP_SAPI != 'cli') {
						$this->redirect('/');
					}
				} else {
					throw new Exception(t('Unable to open config/site_user.php for writing.'));
				}

			} else {
				if ($e->has()) {
					$this->set('error', $e);
				} else {
					$this->set('error', $val->getError());
				}
			}
			
		} catch (Exception $e) {
			$this->reset();
			$this->set('error', $e);
		}
	}

}

