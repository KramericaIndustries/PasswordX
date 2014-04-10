<?php
defined('C5_EXECUTE') or die("Access Denied.");
$config_check_failed = false;

if (version_compare(PHP_VERSION, '5.1.0', '<')) {
	die("Concrete5 requires PHP5.1.");
}

if (!defined('CONFIG_FILE')) { 
	define('CONFIG_FILE', DIR_CONFIG_SITE . '/site.php');
}

//remove admin recovery key if exists
if( file_exists(DIR_CONFIG_SITE . '/recovery/recovery_key.rsa') ) {
	if( unlink(DIR_CONFIG_SITE . '/recovery/recovery_key.rsa') === false ) {
		throw new Exception("Could not remove recovery key. Please do it manually!");
	}
}

if (file_exists(CONFIG_FILE)) {
	include(CONFIG_FILE);
} else {
	// nothing is installed
	$config_check_failed = true;
}