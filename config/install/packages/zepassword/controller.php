<?php

class ZepasswordStartingPointPackage extends StartingPointPackage {

	protected $pkgHandle = 'zepassword';
	
	public function getPackageName() {
		return t('Password System');
	}
	
	public function getPackageDescription() {
		return t('Password System Application');
	}
	
}