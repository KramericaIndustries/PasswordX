<?php
defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Concrete5 page object extended
 */
class Page extends Concrete5_Model_Page {
	
	/**
	 * rescanSystemPage
	 * For reasons that are from another world, C5 hardcodes the system pages list
	 * Lets extend this list with the ones defined by us
	 */
	public function rescanSystemPageStatus() {
		$cID = $this->getCollectionID();
		$db = Loader::db();
		$newPath = $db->GetOne('select cPath from PagePaths where cID = ? and ppIsCanonical = 1', array($cID));
		// now we mark the page as a system page based on this path:
		$systemPages=array(
			'/login', '/register', '/!trash', '/!stacks', '/!drafts', '/!trash/*', '/!stacks/*', '/!drafts/*', '/download_file', '/profile', '/dashboard', '/profile/*', '/dashboard/*','/page_forbidden','/page_not_found','/members',
			'/searchpass', '/ajax', '/sys'		
		); 
		$th = Loader::helper('text');
		$db->Execute('update Pages set cIsSystemPage = 0 where cID = ?', array($cID));
		foreach($systemPages as $sp) {
			if ($th->fnmatch($sp, $newPath)) {
				$db->Execute('update Pages set cIsSystemPage = 1 where cID = ?', array($cID));
			}
		}				
	}
	
}