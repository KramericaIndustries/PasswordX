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
	 * Check for additional requirements for the app to run
	 */
	protected function setRequiredItemsExtended() {
		$this->set('curlTest', function_exists('curl_version'));
	}
	
	/**
	 * We will use our own theme. Make this function a passthough
	 */
	protected function validateSampleContent($e) {
		return $e;
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
			
			$authy = Loader::helper("authy");
			
			if( !$authy->validAPIKey( $_POST['AUTHY_API_KEY'] ) ) {
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
			$val->addRequired("authy-cellphone", t('You must specify a valid phone number'));
			$val->addRequired("countryCode", t('You must specify a valid country'));
			$val->addRequired("DB_DATABASE", t('You must specify a valid database name'));
			$val->addRequired("DB_SERVER", t('You must specify a valid database server'));
			$val->addRequired("AUTHY_API_KEY", t('You must specify a Authy API Key'));
			
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
			$e = $this->validateAuthy($e);
			
			//This is just a gimmick call, for C5 reasons
			$e = $this->validateSampleContent($e);
			
			if ($val->test() && (!$e->has())) {
				
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
					$configuration .= "define('INSTALL_USER_PHONE', '" . addslashes($_POST['authy-cellphone']) . "');\n";
					$configuration .= "define('INSTALL_USER_COUNTRY_CODE', '" . addslashes($_POST['countryCode']) . "');\n";
					$configuration .= "define('AUTHY_API_KEY', '" . addslashes($_POST['AUTHY_API_KEY']) . "');\n";
					$configuration .= "define('SITE', '" . addslashes($_POST['SITE']) . "');\n";
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

