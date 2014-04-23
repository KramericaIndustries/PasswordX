<?php  defined('C5_EXECUTE') or die(_("Access Denied."));

function s2nb($text) {
	return str_replace(' ', '&nbsp;', $text);
}

// Dashboard Style Note: We are **not** using Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper() etc...
// because that changes all the styling for the inner elements as well (and I don't feel like updating all that now).
// So we need to manually wrap in <div class="ccm-dashboard-page-container"> for 5.6 compatibility
// (5.5's dashboard theme automatically wraps page content in that, but 5.6 doesn't -- so in 5.5 we'll have two redundant
// divs with the same class -- but this doesn't effect the styling at all so it's fine).

echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('PasswordX Designer'), false, 'span12', false);
?>
<div class="ccm-pane-body">

	<?php 
	//Success message display
	 if ($is_generated) { ?>
		<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<?php  echo t('<strong>Success:</strong> The blocks has been installed, and will now be available in the "Add Blocks" list when pages are edited.'); ?>
		</div>
	<?php } ?>
	
	<?php  
	//Error display in case the directory is not writable 
	if (!$can_write) { ?>
			<div class="alert alert-danger">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<?php  echo t('<strong>Warning:</strong> The blocks directory is not writeable. Blocks cannot be created from this page until permissions are changed on your server.'); ?>
			</div>
	<?php  } ?>
	
		<!-- Small intro text and user guide link -->
		<div class="row-fluid">
			
		 <div class="span9">
		 <p>This is the PasswordX design center. You can create your own custom blocks for. If you need help, please take a look at the user guide.</p>
		
		 </div>
		 <div class="span3">
		  <a href="<?php echo $this->controller->guide_url; ?>" target="_blank" class="btn primary pull-right"><span class="icon-file icon-white"></span> User Guide</a>
		 </div>
		</div><!-- //row fluid -->

		<form method="post" action="<?php  echo $this->action('generate_block'); ?>" id="designer-content-form" class="form-horizontal">

		<!-- Main info about the block -->
		
			<!-- Block Name -->
			<div class="control-group">
		    <label class="control-label" for="name"><?php echo t('Block Name')?></label>
		    <div class="controls">
		      <input type="text" id="name" name="name" placeholder="<?php echo t('Block Name')?>">
		    </div>
		  </div>
		  
		  <!-- Block handle -->
		  <div class="control-group">
		    <label class="control-label" for="handle"><?php echo t('Block Handle')?></label>
		    <div class="controls">
		      <input type="text" id="handle" name="handle" placeholder="<?php echo t('Block Handle')?>" readonly>
		    </div>
		  </div>
		  
		  <!-- Block Description -->
		  <div class="control-group">
		    <label class="control-label" for="inputPassword"><?php echo t('Block Handle')?></label>
		    <div class="controls">
		      <textarea rows="3" name="description" id="description" placeholder="<?php  echo t('Block Description (optional)'); ?>"></textarea>
		    </div>
		  </div>

	<!-- //Main info about the block -->

			<div id="designer-content-fields">
				
				<label for="fieldDefaultContents[]"><?php  echo t('Default HTML Content'); ?></label><br />
				<textarea rows="4" name="fieldDefaultContents[]" id="fieldDefaultContents[]"></textarea>
				
			</div>

			
			
			<div class="btn-toolbar form-actions">

				<!-- Add new Item -->			  
				<div class="btn-group">
	                <button class="btn dropdown-toggle" data-toggle="dropdown"><?php echo t('Add another field'); ?> <span class="caret"></span></button>
	                <ul class="dropdown-menu">
	                  <li><a href="#"><?php echo t('Text field'); ?></a></li>
	                  <li><a href="#"><?php echo t('Password field'); ?></a></li>
	                  <li><a href="#"><?php echo t('Textarea field'); ?></a></li>
	                  <li class="divider"></li>
	                  <li><a href="#"><?php echo t('WYSIWYG'); ?></a></li>
	                </ul>
	              </div>
				
				<!-- Submit Buttons -->
				<?php  if ($can_write) { ?>
					<div class="btn-group">
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

		</form>

		<script type="text/javascript">
		var VALIDATE_HANDLE_URL = '<?php  echo $validate_handle_url; ?>';

		//For translations (generated by php t() function):
		var FIELDTYPE_LABELS = {
			'static': '<?php  echo t("Static HTML"); ?>',
			'textbox': '<?php  echo t("Textbox Field"); ?>',
			'textarea': '<?php  echo t("Text Area Field"); ?>',
			'image': '<?php  echo t("Image Field"); ?>',
			'file': '<?php  echo t("File Download Field"); ?>',
			'link': '<?php  echo t("Page Link Field"); ?>',
			'url': '<?php  echo t("External URL Field"); ?>',
			'date': '<?php  echo t("Date Picker Field"); ?>',
			'select': '<?php  echo t("Dropdown List"); ?>',
			'wysiwyg': '<?php  echo t("WYSIWYG Editor"); ?>'
		};
		var ERROR_MESSAGES = {
			'name_required': '<?php  echo t("Block Name is required."); ?>',
			'handle_required': '<?php  echo t("Block Handle is required."); ?>',
			'handle_length': '<?php  echo t("Block Handle cannot exceed 32 characters in length."); ?>',
			'handle_lowercase': '<?php  echo t("Block Handle can only contain lowercase letters and underscores."); ?>',
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