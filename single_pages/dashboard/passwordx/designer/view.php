<?php  defined('C5_EXECUTE') or die(_("Access Denied."));
/**
 * Block designer - inspired by Designer Content: http://www.concrete5.org/marketplace/addons/designer-content/
 * (c) 2014 PasswordX
 * Apache v2 License
 */

function s2nb($text) {
	return str_replace(' ', '&nbsp;', $text);
}

echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Design your own Password Blocks'), false, 'span12', false);
?>
<div class="ccm-pane-body" style="padding-bottom: 0px">

	<?php 
	//Success message display
	 if ($is_generated) { ?>
		<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<?php  echo t('<strong>Success:</strong> The block has been installed, and is now available in the "Add Blocks" list when pages are edited. You can also review it on the <a href="/index.php/dashboard/blocks/types/">Block Types</a> dashboard page, where you can organize your block list or remove it again.'); ?>
		</div>
	<?php } ?>
	
	<?php  
	//Error display in case the directory is not writable 
	if (!$can_write) { ?>
			<div class="alert alert-danger">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<?php  echo t('<strong>Warning:</strong> The /blocks directory is not writeable. Blocks cannot be created until permissions are changed on your server.'); ?>
			</div>
	<?php  } ?>
	
		<!-- Small intro text and user guide link -->
		 <p class="well">This is the PasswordX block designer. Here you can create your own custom password blocks so you can save and organize passwords on your pages just the way you like it. If you've designed a generically applicable block, please contribute it back to the community as it might then be included in PasswordX by default. <strong>Please note:</strong> Once saved, custom blocks cannot be edited but have to be deleted and recreated.</p>

		<form method="post" action="<?php  echo $this->action('generate_block'); ?>" id="designer-content-form" class="form-horizontal">

		<!-- Main info about the block -->
		
			<!-- Block Name -->
			<div class="control-group">
		    <label class="control-label" for="name"><?php echo t('Block Name')?></label>
		    <div class="controls">
		      <input type="text" id="name" name="name" placeholder="<?php echo t('Block Name')?>">
		      <input type="text" id="handle" name="handle" placeholder="<?php echo t('Block Handle')?>" readonly>
		    </div>
		  </div>
		  
		  <!-- Block handle 
		  <div class="control-group">
		    <label class="control-label" for="handle"><?php echo t('Block Handle')?></label>
		    <div class="controls">
		      
		    </div>
		  </div>
		  -->
		  
		  
		  <!-- Block Description -->
		  <div class="control-group">
		    <label class="control-label" for="inputPassword"><?php echo t('Description')?></label>
		    <div class="controls">
		      <textarea rows="3" name="description" id="description" placeholder="<?php  echo t('Block Description (optional)'); ?>"></textarea>
		    </div>
		  </div>

	<!-- //Main info about the block -->

			<ul id="designer-content-fields">
				<!-- <ul id="sortable-content-fields"> -->
				<script id="field-template" type="text/x-jQuery-tmpl">
			        <li>
			        <div class="designer-content-field" data-id="${id}" data-type="${type}">
						
						<input type="hidden" name="fieldIds[]" value="${id}" />
						<input type="hidden" name="fieldTypes[${id}]" value="${type}" />
	
						<div class="designer-input-control">
							<i class="icon-move"></i>
							<i class="icon-trash designer-content-field-delete" data-id="${id}"></i>
						</div>

						<div class="row">
						
							<div class="span2 label-name">
								<strong>${label}</strong>
				            </div>	
						
							<div class="span4">
								{{if type == 'group'}}
									<label for="" class="special-label">Name</label><input type="text" class="designer-content-field-editorlabel-grp" name="fieldLabels[${id}]" id="fieldLabels[${id}]" placeholder="Group Name">
								{{else}}
									<label for="" class="special-label">Input Label:</label><input type="text" class="designer-content-field-editorlabel" name="fieldLabels[${id}]" id="fieldLabels[${id}]" placeholder="Label">
								{{/if}}
							</div>

							{{if type != 'group'}}
							<div class="span5">
							<!--
							<label class="checkbox inline" for="fieldsRequired[${id}]">
  								<input type="checkbox" name="fieldsRequired[${id}]" id="fieldsRequired[${id}]" value="required" checked> Encrypted
							</label>
							-->						
							<input type="hidden" name="fieldsRequired[${id}]" id="fieldsRequired[${id}]" value="required" checked>
							<label class="checkbox inline" for="fieldsSearchable[${id}]">
  								<input type="checkbox" name="fieldsSearchable[${id}]" id="fieldsRequired[${id}]" value="searchable" {{if type != 'password'}} checked {{/if}} > Searchable
							</label>
							<label class="checkbox inline" for="fieldsExportable[${id}]">
  								<input type="checkbox" name="fieldsExportable[${id}]" id="fieldsRequired[${id}]" value="exportable" checked> Exportable
							</label>
							</div>
							{{/if}}
						</div> <!-- //row with text -->

			        </div>
					</li>
			    </script>
			    </ul>
			    
			<!-- </div> -->
			
							<!-- Add new Item -->			  
				<div class="btn-group">
	                <button class="btn dropdown-toggle" data-toggle="dropdown"><?php echo t('Add another field'); ?> <span class="caret"></span></button>
	                <ul class="dropdown-menu">
	                  <li><a href="#" class="add-field-type" data-type="textbox"><?php echo t('Text field'); ?></a></li>
	                  <li><a href="#" class="add-field-type" data-type="password"><?php echo t('Password field'); ?></a></li>
	                  <li><a href="#" class="add-field-type" data-type="textarea"><?php echo t('Textarea field'); ?></a></li>
	                  <li class="divider"></li>
	                  <li><a href="#" class="add-field-type" data-type="wysiwyg"><?php echo t('WYSIWYG'); ?></a></li>
	                  <li class="divider"></li>
	                  <li><a href="#" class="add-field-type" data-type="group"><?php echo t('Group Separator'); ?></a></li>
	                </ul>
	              </div>
			

			<div class="clearfix" style="padding-bottom: 0px;"></div>
			
			<div class="btn-toolbar form-actions">

				<!-- Submit Buttons -->
				<?php  if ($can_write) { ?>
					<div class="btn-group pull-right">
						<button class="btn btn-primary" id="designer-content-submit">
							<i class="icon-wrench icon-white"></i> <?php  echo t('Create The Block'); ?>
						</button>
						
						<button class="btn btn-primary active" id="designer-content-submit-loading" style="display: none;">
							<i class="icon-wrench icon-white"></i> <?php  echo t('Processing...'); ?>
						</button>
					</div>
				<?php } ?>
				<!-- //Submit Buttons -->
				
			</div><!-- //btn-toolbar form-actions -->

			<div class="clearfix"></div>

		</form>

		<script type="text/javascript">
		var VALIDATE_HANDLE_URL = '<?php  echo $validate_handle_url; ?>';

		//For translations (generated by php t() function):
		var FIELDTYPE_LABELS = {
			'textbox': '<?php  echo t("Textbox Field"); ?>',
			'textarea': '<?php  echo t("Text Area Field"); ?>',
			'wysiwyg': '<?php  echo t("WYSIWYG Editor"); ?>',
			'password': '<?php  echo t("Password Field"); ?>',
			'group': '<?php  echo t("Group separator"); ?>'
		};
		var ERROR_MESSAGES = {
			'name_required': '<?php  echo t("Block Name is required."); ?>',
			'handle_required': '<?php  echo t("Block Handle is required."); ?>',
			'handle_length': '<?php  echo t("Block Handle cannot exceed 32 characters in length."); ?>',
			'handle_lowercase': '<?php  echo t("Block Handle can only contain lower-case letters and underscores."); ?>',
			'handle_exists': '<?php  echo t("Block Handle is already in use by another package or block type (or block files already exist in the \"blocks\" directory of your site)."); ?>',
			'table_exists': '<?php  echo t("WARNING: A table with this Block Handle already exists in your database. If you make this block, the existing table will be overwritten and any content stored in it will be permanently deleted!"); ?>',
			'fields_required': '<?php  echo t("You must add at least 1 field."); ?>',
			'labels_required': '<?php  echo t("All fields must have an Editor Label."); ?>',
			'widths_numeric': '<?php  echo t("Image Widths must be valid numbers."); ?>',
			'heights_numeric': '<?php  echo t("Image Heights must be valid numbers."); ?>',
			'options_required': '<?php  echo t("Dropdown Lists must have one or more choices."); ?>',
			'error_header': '<?php  echo t("Cannot proceed! Please correct the following errors:"); ?>'
		};
		</script>

</div><!--pane body -->
<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);?>