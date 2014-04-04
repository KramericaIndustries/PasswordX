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
	 * Overwrite the Configure part of the script
	 */
	public function configure() {	
		try {

			$val = Loader::helper('validation/form');
			$val->setData($this->post());
			$val->addRequired("SITE", t("Please specify your site's name"));
			$val->addRequiredEmail("uEmail", t('Please specify a valid email address'));
			$val->addRequired("DB_DATABASE", t('You must specify a valid database name'));
			$val->addRequired("DB_SERVER", t('You must specify a valid database server'));
			
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
					$configuration .= "define('INSTALL_USER_EMAIL', '" . $_POST['uEmail'] . "');\n";
					$configuration .= "define('INSTALL_USER_PASSWORD_HASH', '" . $hasher->HashPassword($_POST['uPassword']) . "');\n";
					$configuration .= "define('INSTALL_STARTING_POINT', '" . $this->post('SAMPLE_CONTENT') . "');\n";
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

	/**
	 * Handles stuff in here :)
	 */
	/*protected function testAndRunInstall() {
		if (file_exists(DIR_CONFIG_SITE . '/site_install_user.php')) {
			require(DIR_CONFIG_SITE . '/site_install.php');
			@include(DIR_CONFIG_SITE . '/site_install_user.php');
			if (defined('ACTIVE_LOCALE') && Localization::activeLocale() !== ACTIVE_LOCALE) {
				Localization::changeLocale(ACTIVE_LOCALE);
			}
			$e = Loader::helper('validation/error');
			$e = $this->validateDatabase($e);
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
				$this->set('successMessage', t('Congratulations. concrete5 has been installed. You have been logged in as <b>%s</b> with the password you chose. If you wish to change this password, you may do so from the users area of the dashboard.', USER_SUPER, $uPassword));
			}
		}
	}*/	

}

