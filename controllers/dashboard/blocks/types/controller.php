<?php
defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Overwrites the block uninstall so that it removes residual files on the disk
 */
class DashboardBlocksTypesController extends Concrete5_Controller_Dashboard_Blocks_Types {

	/**
	 * Recursevly removes a dir
	 */
	private function rrmdir($dir) { 
	  foreach(glob($dir . '/*') as $file) { 
	    if(is_dir($file)) rrmdir($file); else unlink($file); 
	  } 
	  rmdir($dir); 
	}
	
	/**
	 * Uninstall the block
	 */
	public function uninstall($btID = 0, $token = '') {
		
		$valt = Loader::helper('validation/token');

		if ($btID > 0) {
			$bt = BlockType::getByID($btID);
		}

		$u = new User();
		if (!$u->isSuperUser()) {
			
			$this->error->add(t('Only the super user may remove block types.'));
			
		} else if (isset($bt) && ($bt instanceof BlockType)) {
			if (!$valt->validate('uninstall', $token)) {
				$this->error->add($valt->getErrorMessage());
			} else if ($bt->canUnInstall()) {
				
				//delete from db?
				$bt->delete();
				
				//and from the disk, if encrypted type
				$handle = $bt->btHandle;
				if( substr($handle, 0,10) == "encrypted_" ) {
					$block_location = DIR_BASE . "/blocks/" . $handle;
					if( file_exists( $block_location ) ) {
						$this->rrmdir( $block_location );	
					}
				}
				
				//and drop the database
				Loader::library('3rdparty/block_generator');
				$table_name = DesignerContentBlockGenerator::tablename($handle);
				Loader::db()->Execute("DROP TABLE IF EXISTS {$table_name}");
						
				$this->redirect('/dashboard/blocks/types', 'block_type_deleted');
			
			} else {
				$this->error->add(t('This block type is internal. It cannot be uninstalled.'));
			}
			
		} else {
			
			$this->error->add('Invalid block type.');
		}
		
		if ($this->error->has()) {
			$this->set('error', $this->error);
		}
		
		$this->inspect($btID);
	}
	
}