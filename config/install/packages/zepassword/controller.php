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
	
}