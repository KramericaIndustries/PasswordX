<?php  defined('C5_EXECUTE') or die("Access Denied.");
/*
 Controller for Projects Single Page
 JESU Website
 (c) 2013 Hammertime (www.hammerti.me)
 SF
*/

class AjaxController extends Controller { 
 
        public function on_start() {
        
                 parent::on_start(); //Call back into the BaseController
        
        }
        
        
        
        public function view($message = null, $error = null) {
		
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

		/* *
		 * I am too lasy to do this by hand again, so I have copied it from another project :)
		 */
		private function ajax_return( $obj ) {
                $json = Loader::helper('json');
                print $json->encode($obj);
                exit();
        }
		
}

