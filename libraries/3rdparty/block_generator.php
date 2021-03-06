<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));

// NOTE: WE DO NO VALIDATION HERE!!!
// MAKE SURE YOU VALIDATE THAT NOTHING IMPORTANT IS GETTING OVERWRITTEN BEFORE USING THIS!!!!!
class DesignerContentBlockGenerator {
	
	private $fields = array();
	
	private $handle;
	private $name;
	private $description;

	private $outpath;
	private $tplpath;
	
	private $chmod = false; //If this gets set, all written files will be chmod()'ed to it.
	
	//Pass in an octal number, e.g. 0666 (see http://php.net/chmod#refsect1-function.chmod-parameters)
	public function set_chmod($chmod) {
		if (is_int($chmod)) {
			$this->chmod = $chmod;
		}
	}
	
	public function add_textbox_field($label, $encrypted = false, $searchable = false, $editable = false) {
		$this->fields[] = array(
			'num' => count($this->fields) + 1,
			'type' => 'textbox',
			'label' => $label,
			'prefix' => '',
			'suffix' => '',
			'encrypted' => $encrypted,
			'searchable' => $searchable,
			'editable' => $editable,
			'maxlength' => 0
		);
	}
	
	public function add_textarea_field($label, $encrypted = false, $searchable = false, $editable = false) {
		$this->fields[] = array(
			'num' => count($this->fields) + 1,
			'type' => 'textarea',
			'label' => $label,
			'prefix' => '',
			'suffix' => '',
			'encrypted' => $encrypted,
			'searchable' => $searchable,
			'editable' => $editable,
			'maxlength' => 0
		);
	}

	public function add_wysiwyg_field($label, $encrypted = false, $searchable = false, $editable = false) {
		$this->fields[] = array(
			'num' => count($this->fields) + 1,
			'type' => 'wysiwyg',
			'label' => $label,
			'prefix' => '',
			'suffix' => '',
			'encrypted' => $encrypted,
			'searchable' => $searchable,
			'editable' => $editable,
			'maxlength' => 0,
			'default'	=> ''
		);
	}
	
	public function add_password_field($label, $encrypted = false, $searchable = false, $editable = false) {
		$this->fields[] = array(
			'num' => count($this->fields) + 1,
			'type' => 'password',
			'label' => $label,
			'prefix' => '',
			'suffix' => '',
			'encrypted' => $encrypted,
			'searchable' => $searchable,
			'editable' => $editable,
			'maxlength' => 0
		);
	}
	
	public function add_group_delimiter( $group_name ) {
		$this->fields[] = array(
				'num' => count($this->fields) + 1,
				'type' => 'group',
				'label' => $group_name,
				'prefix' => '',
				'suffix' => '',
				'encrypted' => false,
				'searchable' => false,
				'editable' => false,
				'maxlength' => 0
		);
	}

	public function generate($handle, $name, $description = '') {
		$this->handle = $handle;
		$this->name = $name;
		$this->description = $description;
		$this->outpath = DIR_FILES_BLOCK_TYPES . "/{$handle}/";
		$this->tplpath = DIR_BASE . '/config/generator_templates/';
		
		$this->create_block_directory();
		$this->generate_controller_php();
		$this->generate_view_php();
		$this->generate_icon_png();
		
		if ($this->has_editable_fields()) {
			$this->generate_add_php();
			$this->generate_auto_js();
			$this->generate_db_xml();
			$this->generate_edit_php();
		}
	}
		
	
/*** GENERATORS ***/
	private function create_block_directory() {
		mkdir(rtrim($this->outpath, '/'));
	}
		
	private function generate_add_php() {
		//No replacements
		$filename = 'add.php';
		$this->copy_file($this->tplpath.$filename, $this->outpath.$filename);
	}
	
	private function generate_auto_js() {
		$filename = 'auto.js';
		
		//Load template
		$template = file_get_contents($this->tplpath.$filename);
		
		//Replace validation rules
		$code = '';
		
		$needsPasswordJS = false;
				
		foreach ($this->fields as $field) {
				
			$field_label = $this->addslashes_single($field['label']);
			
			if ($field['type'] == 'password') {
				$needsPasswordJS = true;
			}
			
		}
		$token = '[[[GENERATOR_REPLACE_VALIDATIONRULES]]]';
		$template = str_replace($token, $code, $template);
		
		//add js speficic to password
		$passwd_code = '';
		if($needsPasswordJS) {
			$passwd_code = '$(function(){$(".sugest_pass").click(function(){$("#"+$(this).data("target")).val(sanePassword());return false});$(".clear_view").mousedown(function(){$("#miror_"+$(this).data("target")).val($("#"+$(this).data("target")).val());$("#"+$(this).data("target")).css("display","none");$("#miror_"+$(this).data("target")).css("display","inline");return false});$(".clear_view").mouseup(function(){$("#"+$(this).data("target")).css("display","inline");$("#miror_"+$(this).data("target")).css("display","none");return false});$(".clear_view").click(function(){return false})})';	
		}
		
		$token = '[[[GENERATIR_PASSWORD_JS]]]';
		$template = str_replace($token, $passwd_code, $template);
		
		//Output file (if we have anything to put in it)
		if ( !empty($code) || !empty($passwd_code) ) {
			$this->output_file($this->outpath.$filename, $template);
		}
	}
		
	private function generate_controller_php() {
		$filename = 'controller.php';
		//Load template
		$template = file_get_contents($this->tplpath.$filename);
		
		//this mambo jumbo approach does not make it for me
		//lets rewrite it, in a more elegant way
		
		//Replace class properties
		$template = str_replace('[[[GENERATOR_REPLACE_CLASSNAME]]]', $this->controllername($this->handle), $template);
		$template = str_replace('[[[GENERATOR_REPLACE_TABLEDEF]]]', ($this->has_editable_fields() ? "\tprotected \$btTable = '".$this->tablename($this->handle)."';\n\t" : "\t"), $template);
		$template = str_replace('[[[GENERATOR_REPLACE_NAME]]]', $this->addslashes_single($this->name), $template);
		$template = str_replace('[[[GENERATOR_REPLACE_DESCRIPTION]]]', $this->addslashes_single($this->description), $template);
		
		$include_helper = array(
			'wysiwyg'	=> false
		);
		
		$name_suffix = array(
			'textbox' => 'textbox_text',
			'password' => 'textbox_text',
			'textarea' => 'textarea_text',
			'wysiwyg' => 'wysiwyg_content',
		);
		
		$encr_fields = array();
		$all_fields = array();
		$search_fields = array();
		
		foreach ($this->fields as $field) {
			
			$thisSufix = $name_suffix[ $field['type'] ];
			
			if ($field['type'] == 'wysiwyg') {
				$include_helper['wysiwyg'] = true;
			}
			
			//push it to the all fields list
			$all_fields[] = "'field_{$field['num']}_{$thisSufix}'";
			
			//should this field be encrypted
			if($field['encrypted']) {
				$encr_fields[] = "'field_{$field['num']}_{$thisSufix}'";
			}
			
			//is the field searchable?
			if($field['searchable']) {
				$search_fields[] = "'field_{$field['num']}_{$thisSufix}'";
			}
		}
		
		//now that we gathered all the needed data, lets build the controller
		
		//subtemplates
		$code = $include_helper['wysiwyg']  ? file_get_contents($this->tplpath.'controller_content_helper.php') : '';
		$token = '[[[GENERATOR_REPLACE_CONTENTHELPER]]]';
		$template = str_replace($token, $code, $template);
		
		//all fields
		$code = "\tprotected \$all_fields = array(";
		$code .= implode(',',$all_fields);
		$code .= ");\n";
		
		$token = '[[[GENERATOR_REPLACE_ALL_FIELDS]]]';
		$template = str_replace($token, $code, $template);
		
		//encrypted fields
		$code = "\tprotected \$encrypted_fields = array(";
		if( !empty($encr_fields) ) {
			 $code .= implode(',',$encr_fields);
		}
		$code .= ");\n";
		
		$token = '[[[GENERATOR_REPLACE_ENCRYPTED_FIELDS]]]';
		$template = str_replace($token, $code, $template);
		
		//searchable fields
		$code = "\tprotected \$searchable_fields = array(";
		if( !empty($search_fields) ) {
			$code .= implode(',',$search_fields);
		}
		$code .= ");\n";
		$token = '[[[GENERATOR_REPLACE_SEARCH_FIELDS]]]';
		$template = str_replace($token, $code, $template);

		//Output file
		$this->output_file($this->outpath.$filename, $template);
	}
	
	private function generate_db_xml() {
		$filename = 'db.xml';
		
		//Load template
		$template = file_get_contents($this->tplpath.$filename);
		
		//Replace table name
		$template = str_replace('[[[GENERATOR_REPLACE_TABLENAME]]]', $this->tablename($this->handle), $template);
		
		//Replace Field definitions
		$code = '';
		foreach ($this->fields as $field) {
			if ($field['type'] == 'textbox') {
				$code .= "\t\t<field name=\"field_{$field['num']}_textbox_text\" type=\"X\"></field>\n\n";
			}
			if ($field['type'] == 'password') {
				$code .= "\t\t<field name=\"field_{$field['num']}_textbox_text\" type=\"X\"></field>\n\n";
			}
			if ($field['type'] == 'textarea') {
				$code .= "\t\t<field name=\"field_{$field['num']}_textarea_text\" type=\"X\"></field>\n\n";
			}
			if ($field['type'] == 'wysiwyg') {
				$code .= "\t\t<field name=\"field_{$field['num']}_wysiwyg_content\" type=\"X2\"></field>\n\n";
			}
		}
		
		$token = '[[[GENERATOR_REPLACE_FIELDS]]]';
		$template = str_replace($token, $code, $template);
	
		//Output file
		$this->output_file($this->outpath.$filename, $template);
	}
	
	private function generate_edit_php() {
		$filename = 'edit.php';
		
		//Load template
		$template = file_get_contents($this->tplpath.$filename);
		
		//Replace html form fields
		$include_asset_library = false;
		$include_page_selector = false;
		$include_date_time = false;
		$include_editor_config = false;
		$code = '';
		
		foreach ($this->fields as $field) {
			
			$label_code = '';
			$input_code = '';
			
			switch( $field['type'] ) {
				
				case 'textbox':
					$label_code = "\t<label for=\"field_{$field['num']}_textbox_text\" class=\"col-lg-2 control-label\">{$field['label']}</label>\n";
					$input_code .= "\t\t\t<?php  echo \$form->text('field_{$field['num']}_textbox_text', \$field_{$field['num']}_textbox_text, array('style' => '', 'placeholder' => '{$field['label']}...', 'class'=>'form-control', 'autocomplete' => 'off')); ?>\n";
					break;
				
				
				case 'textarea':
					$label_code = "\t<label for=\"field_{$field['num']}_textarea_text\" class=\"col-lg-2 control-label\">{$field['label']}</label>\n";
					$input_code .= "\t<textarea class=\"form-control\" placeholder=\"{$field['label']}...\" id=\"field_{$field['num']}_textarea_text\" name=\"field_{$field['num']}_textarea_text\" rows=\"5\"><?php  echo \$field_{$field['num']}_textarea_text; ?></textarea>\n";
					break;
				
								
				case 'password':
					$label_code = "\t<label for=\"field_{$field['num']}_textbox_text\" class=\"col-lg-2 control-label\">{$field['label']}</label>\n";
					
					$input_code .= "\t\t\t<?php  echo \$form->password('field_{$field['num']}_textbox_text', \$field_{$field['num']}_textbox_text, array('style' => 'width: 45%; display: inline;' , 'placeholder' => '{$field['label']}...', 'class'=>'form-control', 'autocomplete' => 'off' )); ?>\n";
					$input_code .= "\t\t\t<input id=\"miror_field_{$field['num']}_textbox_text\" type=\"text\" class=\"form-control\" style=\"width: 45%; display: none;\" value=\"\" />\n";
					$input_code .= "\t\t\t<button class=\"btn btn-primary sugest_pass\" data-target=\"field_{$field['num']}_textbox_text\"> Suggest a password</button>\n";
					$input_code .= "\t\t\t<button class=\"btn btn-danger clear_view\" data-target=\"field_{$field['num']}_textbox_text\"> Clearview the password</button>\n";
					break;
					 
					
				case 'wysiwyg':
					$label_code = "\t<label class=\"col-lg-2 control-label\">{$field['label']}</label>\n";
					$include_editor_config = true; 
					$input_code .= "\t\t\t<?php  Loader::element('editor_controls'); ?>\n";
					$input_code .= "\t\t\t<textarea id=\"field_{$field['num']}_wysiwyg_content\" name=\"field_{$field['num']}_wysiwyg_content\" class=\"ccm-advanced-editor\"><?php  echo \$field_{$field['num']}_wysiwyg_content; ?></textarea>\n";
					break;
							
			}
			
			//prefix wrapper
			$code .= "<div class=\"form-group\">\n";
			$code .= $label_code;
			$code .= "\t\t<div class=\"col-lg-10\">\n";
    		
			$code .= $input_code;
			
			//suffix wrapper
    		$code .= "\t\t</div>\n";
			$code .= "</div>\n";

		}
		
		$token = '[[[GENERATOR_REPLACE_FIELDS]]]';
		$template = str_replace($token, $code, $template);
	
		//Replace helpers (if needed)
		$code = '';
		$code .= $include_editor_config ? "Loader::element('editor_config');\n" : '';
		$token = '[[[GENERATOR_REPLACE_HELPERLOADERS]]]';
		$template = str_replace($token, $code, $template);
	
		//Output file
		$this->output_file($this->outpath.$filename, $template);
	}
	
	private function generate_icon_png() {
		//No replacements
		$filename = 'icon.png';
		$this->copy_file($this->tplpath.$filename, $this->outpath.$filename);
	}
	
	private function generate_view_php() {
		$filename = 'view.php';
		
		//Load template
		$template = file_get_contents($this->tplpath.$filename);
		
		//Replace html
		$code = '';
		$include_navigation_helper = false;
		foreach ($this->fields as $field) {
			
			if ($field['type'] == 'static') {
				$code .= $field['static'] . "\n\n";
			}

			if ($field['type'] == 'textbox') {
				$code .= "<?php  if (!empty(\$field_{$field['num']}_textbox_text)){ ?>\n";
				
				$code .= "<div class=\"credentials-fields\">\n";
				$code .= "<label for=\"pass-block-username\" class=\"control-label\">{$field['label']}:</label>\n";
				$code .= "<input name=\"pass-block-username\" type=\"text\" class=\"pass-block-username\" value=\"<?php  echo htmlentities(\$field_{$field['num']}_textbox_text, ENT_QUOTES, APP_CHARSET); ?>\" readonly>\n";
				$code .= "</div>";
				
				$code .= "<?php } ?>\n\n";

			}
			
			if ($field['type'] == 'password') {
				$code .= "<?php  if (!empty(\$field_{$field['num']}_textbox_text)){ ?>\n";
			
				$code .= "<div class=\"credentials-fields\">\n";
				$code .= "<label for=\"pass-block-password\" class=\"control-label\">Password:</label>\n";
				$code .= "<span class=\"password_super_block\">\n";
				$code .= "<span class=\"password_block_hash\">. </span>\n";
				$code .= "<span class=\"password_block\">\n";
				$code .= "<input name=\"pass-block-password\" type=\"text\" class=\"password_textbox\" value=\"<?php  echo htmlentities(\$field_{$field['num']}_textbox_text, ENT_QUOTES, APP_CHARSET); ?>\" readonly>\n";
				$code .= "</span>\n";
				$code .= "</span>\n";
				$code .= "</div>\n";
			
				$code .= "<?php } ?>\n\n";
			
			}

			if ($field['type'] == 'textarea') {
				$code .= "<?php  if (!empty(\$field_{$field['num']}_textarea_text)){ ?>\n";
				
				$code .= "<div>\n";
				$code .= "<span class=\"username-label\">{$field['label']}:</span><br/>\n";
				$code .= "<span class=\"notes\"><?php  echo nl2br(htmlentities(\$field_{$field['num']}_textarea_text, ENT_QUOTES, APP_CHARSET)); ?></span>\n";
				$code .= "</div>\n"; 
				
				$code .= "<?php  } ?>\n\n";
			
			}


			if ($field['type'] == 'wysiwyg') {
				
				$code .= "<?php  if (!empty(\$field_{$field['num']}_wysiwyg_content)){ ?>\n";
				
				$code .= "<div>\n";
				$code .= "<span class=\"username-label\">{$field['label']}:</span><br/>\n";
				$code .= "<span class=\"notes\"><?php  echo \$field_{$field['num']}_wysiwyg_content; ?></span>\n";
				$code .= "</div>\n";
				
				$code .= "<?php  } ?>\n\n";

			}
			
		}
		$token = '[[[GENERATOR_REPLACE_HTML]]]';
		$template = str_replace($token, $code, $template);
		
		//Replace helpers (if needed)
		$code = '';
		$code .= $include_navigation_helper ? "\$nh = Loader::helper('navigation');\n" : '';
		$token = '[[[GENERATOR_REPLACE_HELPERLOADERS]]]';
		$template = str_replace($token, $code, $template);
				
		//Output file
		$this->output_file($this->outpath.$filename, $template);
	}
	
	
	//Funnel all file writes/copies through one of these so we can apply file permissions if needed...
	//
	private function output_file($topath, $contents) {
		file_put_contents($topath, $contents);
		$this->set_file_permission($topath);
	}
	//
	private function copy_file($frompath, $topath) {
		copy($frompath, $topath);
		$this->set_file_permission($topath);
	}
	
	private function set_file_permission($filepath) {
		if ($this->chmod !== false) {
			@chmod($filepath, $this->chmod);
		}		
	}
	
/*** UTILITY FUNCTIONS ***/
	public static function tablename($handle) {
		//NOTE: As of 2.0, we're pre-pending "DC" to the tablename
		//		(as a precaution because we now overwrite existing tables,
		//		 so hopefully this prefix "namespaces" our generated tables
		//		 enough to avoid conflicting with some other legitemate table).
		return 'btDC' . DesignerContentBlockGenerator::camelcase($handle);
	}
	
	public static function controllername($handle) {
		return DesignerContentBlockGenerator::camelcase($handle) . 'BlockController';
	}
	
	public static function camelcase($handle) {
		$th = Loader::helper('text');
		return $th->camelcase($handle);
	}
	
	private function addslashes_single($s) {
		//Escape single quotes and backslashes only (not double quotes) -- intended for insertion into js or php single-quoted string.
		$s = str_replace("\\", "\\\\", $s);
		$s = str_replace("'", "\\'", $s);
		return $s;
	}
	
	private function has_editable_fields() {
		$nonEditableFieldCount = 0;
		foreach ($this->fields as $field) {
			if ($field['type'] == 'static') {
				$nonEditableFieldCount++;
			}
		}
		return (count($this->fields) > $nonEditableFieldCount);
	}
}
