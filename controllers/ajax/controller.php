<?php  defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Ajax methods controller
 * Responsible for page actions in the front-end
 * (c) 2014 PasswordX
 * Apache v2 License
 */

class AjaxController extends Controller { 
 
        public function on_start() {
                 parent::on_start(); //Call back into the BaseController
        }
        
        
        public function view($message = null, $error = null) {
		}

		
		/* *
		 * Generic Json return
		 */
		private function ajax_return( $obj ) {
                $json = Loader::helper('json');
                print $json->encode($obj);
                exit();
        }		
		
		
		/*
		 * Create a new item in the structure. You get here by ajax request :)
		 */
		public function addnewitem( $name = null, $type = null, $parent_id = null ) {
		
			$parent_node = Page::getByID( (int)$parent_id );
			
			$newPageData = array( 
                                'cName'         => $name, 
                                'cDescription'  => $name . " description", 
                                'cDatePublic'   => date('YY-MM-DD', strtotime('now'))
            );
			
			$ct = CollectionType::getByHandle( ($type == "secret" ? "passpack" : "container" ) );
			
			$new_cID = $parent_node->add( $ct, $newPageData );
			
			if( $new_cID ) {
				$this->ajax_return( array(
                                'status' => "OK",
								'new_cID' => $new_cID->getCollectionID(),
                ));
			}

		}
		
		/*
		 * Deletes an item (page)
		 */
		public function deleteitem( $collection_id ) {
		
		 if (!$collection_id) {
		  $this->ajax_return("Collection id missing.");
		  return false;
		 }
		
		 $collection = Page::getByID( (int)$collection_id );
		  
		 if (is_object($collection) && (!$collection->error)) {
		 
			$collection->delete();
			
			$this->ajax_return( array(
                'status' => 'DELETED',
				'cID' => $collection_id
             ));		 
			
		 } else {
			
			$this->ajax_return( array(
                'status' => 'ERROR',
				'message' => 'Invalid collection ID'
             ));
			
		 }
			


		}
		
		/*
		 * Renames an item (page)
		 */
		public function renameitem( $newname, $collection_id ) {
		
		 if (!$collection_id || !$newname) {
			 $this->ajax_return( array(
                'status' => 'ERROR',
				'message' => 'One or more arguments missing.'
             ));
		  return false;
		 }
		
			$collection = Page::getByID( (int)$collection_id );
			
			if (is_object($collection) && (!$collection->error)) {
			 
			 //$collection->delete();
			 $newPageData = array( 'cName' => $newname ); 
			 $collection->update($newPageData);
			
			 $this->ajax_return( array(
                'status' => 'RENAMED',
				'cID' => $collection_id
             ));
			 
			} else {
			
			 $this->ajax_return( array(
                'status' => 'ERROR',
				'message' => 'Invalid collection ID'
             ));
			
			}
		}

			
		
}

