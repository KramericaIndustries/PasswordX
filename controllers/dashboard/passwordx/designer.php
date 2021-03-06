<?php
/**
 * Controller for designer
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied.");

class DashboardPasswordxDesignerController extends DashboardBaseController {
	
	
	public $helpers = array('form'); //makes form helper available to the single_page

	function view() {
		$html = Loader::helper('html');
		$this->addHeaderItem($html->javascript('jquery.tmpl.min.js', 'designer_content'));
		$this->addHeaderItem($html->javascript('designer_content_dashboard_ui.js', 'designer_content'));
		$this->addHeaderItem($html->javascript('bootstrap-dropdown.js'));
		
		$this->addHeaderItem($html->css('designer_content_dashboard_ui.css', 'designer_content'));
		
		$th = Loader::helper('concrete/urls'); 
		$this->set('validate_handle_url', $th->getToolsURL('validate_handle'));
		
		$generated_handle = $this->get('generated');
		$generated_name = $this->block_name_for_handle($generated_handle);
		$this->set('is_generated', !empty($generated_handle));
		$this->set('generated_name', $generated_name);
		
		$this->set('can_write', is_writable(DIR_FILES_BLOCK_TYPES));
	}
	
	private function block_name_for_handle($handle) {
		if (empty($handle)) {
			return '';
		} else {
			$bt = BlockType::getByHandle($handle);
			return is_object($bt) ? $bt->getBlockTypeName() : '';
		}
	}
	
	public function generate_block() { //In single_pages, do not prepend "action_" (unlike blocks)
		//All validation happened in the front-end prior to submission.
		//But just in case... re-validate a few key things (especially that we're not going to overwrite something that already exists)
		$handle = $this->post('handle');
		$name = $this->post('name');
		$name = empty($name) ? '' : strip_tags($name);
		$description = $this->post('description');
		$description = empty($description) ? '' : strip_tags($description);
		
		if (!is_writable(DIR_FILES_BLOCK_TYPES)) {
			die(t('Error: Blocks directory is not writeable!'));
		} else if (empty($handle) || empty($name)) {
			die(t('Error: Block handle or name is missing!'));
		} else if (!$this->validate_unique_handle($handle)) {
			die(t("Error: Block Handle is already in use by another package or block type (or block files already exist in the \"blocks\" directory of your site)!"));
		}
		
		
		
		//Gather all field data
		$field_ids = $this->post('fieldIds'); //The order of id's in this array reflects the user's chosen output order of the fields.
		$field_types = $this->post('fieldTypes');
		$field_labels = $this->post('fieldLabels');
		
		$fields_required = $this->post('fieldsRequired');
		$fields_searchable = $this->post('fieldsSearchable');
		$fields_exportable = $this->post('fieldsExportable');
		
		//Set up the code generator
		Loader::library('3rdparty/block_generator');
		$block = new DesignerContentBlockGenerator();
		
		if (defined('DESIGNER_CONTENT_FILE_CHMOD')) {
			$block->set_chmod(DESIGNER_CONTENT_FILE_CHMOD);
		}
		
		
		foreach ($field_ids as $id) {
			$type = $field_types[$id];
			
			switch( $type ) {
				case 'textbox':
					$block->add_textbox_field( 
						$field_labels[$id], 
						!empty($fields_required[$id]),
						!empty($fields_searchable[$id]),
						!empty($fields_exportable[$id])
					);
					break;
				
				case 'textarea':
					$block->add_textarea_field( 
						$field_labels[$id], 
						!empty($fields_required[$id]),
						!empty($fields_searchable[$id]),
						!empty($fields_exportable[$id])
					);
					break;
				
				case 'password':
					$block->add_password_field( 
						$field_labels[$id], 
						!empty($fields_required[$id]),
						!empty($fields_searchable[$id]),
						!empty($fields_exportable[$id])
					);
					break;
				
				case 'wysiwyg':
					$block->add_wysiwyg_field( 
						$field_labels[$id], 
						!empty($fields_required[$id]),
						!empty($fields_searchable[$id]),
						!empty($fields_exportable[$id])
					);
					break;
					
				case 'group':
					$block->add_group_delimiter( $field_labels[$id] );
					break;
			}
			
		}
		
		//Make+install block
		$block->generate($handle, $name, $description);
		$this->drop_existing_table($handle);
		
		BlockType::installBlockType($handle);
		
		$this->bubbleUp( $handle );
		
		//Redirect back to view page so browser refresh doesn't trigger a re-generation
		header('Location: ' . View::url("/dashboard/passwordx/designer/?generated={$handle}"));
		exit;
	}

	/**
	 * Moves the block up in the display order, after the last encrypted block type
	 */
	private function bubbleUp( $handle ){

		//INIT DB
		$db = Loader::db();
		
		//figure out the position for the element
		$q="SELECT btID FROM BlockTypes where btHandle LIKE 'encrypted_%'";
		$block_position = $db->Execute($q)->RecordCount();
		
		//and set it up
		$q="UPDATE BlockTypes SET btDisplayOrder=? WHERE btHandle=?";
		$db->Execute($q, array( $block_position, $handle ));
		
		//finally, move the rest down
		$q="UPDATE BlockTypes SET btDisplayOrder= btDisplayOrder + 1 WHERE btHandle NOT LIKE 'encrypted_%' AND btHandle NOT LIKE 'core_%' AND btHandle NOT LIKE 'dashboard_%'";
		$db->Execute($q);
		
	} 
	
	private function drop_existing_table($handle) {
		Loader::library('3rdparty/block_generator');
		$table_name = DesignerContentBlockGenerator::tablename($handle);
		Loader::db()->Execute("DROP TABLE IF EXISTS {$table_name}"); //cannot use parameterized query here (it surrounds the table name in quotes which is a MySQL error)
	}
	
	public function validate_unique_handle($handle) {
		$db = Loader::db();
		
		$pkg_exists = $db->GetOne("SELECT COUNT(*) FROM Packages WHERE pkgHandle = ?", array($handle));

		$block_exists = $db->GetOne("SELECT COUNT(*) from BlockTypes where btHandle = ?", array($handle));

		$dir_exists = is_dir(DIR_FILES_BLOCK_TYPES_CORE . '/' . $handle) || is_dir(DIR_FILES_BLOCK_TYPES . '/' . $handle);
		
		return (!$pkg_exists && !$block_exists && !$dir_exists);
	}
	
	public function validate_unique_tablename_for_handle($handle) {
		Loader::library('3rdparty/block_generator');
		$tables = Loader::db()->MetaTables('TABLES');
		$table_name = DesignerContentBlockGenerator::tablename($handle);
		$table_exists = in_array($table_name, $tables);
		return !$table_exists;
	}
	
}
