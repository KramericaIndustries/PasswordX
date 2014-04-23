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

			<hr />

			<div id="designer-content-fields">
				<script id="field-template" type="text/x-jQuery-tmpl">
		        <div class="designer-content-field" data-id="${id}" data-type="${type}">
					<input type="hidden" name="fieldIds[]" value="${id}" />
					<input type="hidden" name="fieldTypes[${id}]" value="${type}" />

					<div class="designer-content-field-header">
						<div class="designer-content-field-title">
							<b>${label}</b>
							&nbsp;
							[<a href="#" class="designer-content-field-delete" data-id="${id}"><?php  echo t('delete'); ?></a><span class="designer-content-field-delete-confirm" data-id="${id}" style="display: none;">Are you sure? <a href="#" class="designer-content-field-delete-yes" data-id="${id}"><?php  echo t('Yes'); ?></a> / <a href="#" class="designer-content-field-delete-no" data-id="${id}"><?php  echo t('No'); ?></a></span>]
						</div>
						<div class="designer-content-field-move" data-id="${id}">
							<span class="designer-content-field-move-up" data-id="${id}">
							[<a href="#" data-id="${id}"><?php  echo t('Move Up'); ?> &uarr;</a>]
							</span>

							&nbsp;&nbsp;

							<span class="designer-content-field-move-down" data-id="${id}">
							[<a href="#" data-id="${id}"><?php  echo t('Move Down'); ?> &darr;</a>]
							</span>
						</div>
					</div>
	
					{{if type == 'static'}}
					<div class="designer-content-field-options static-html-field">
						<textarea rows="2" name="fieldStaticHtml[${id}]" id="fieldStaticHtml[${id}]"></textarea>
						<label><?php  echo t('Anything entered here will be directly outputted to the block view &mdash; users will not be able to edit it.'); ?></label>
					</div>
					{{else type == 'select'}}
					<div class="designer-content-field-options">
						<label for="fieldLabels[${id}]"><?php  echo t('Editor Label'); ?></label><br />
						<input type="text" class="designer-content-field-editorlabel" name="fieldLabels[${id}]" id="fieldLabels[${id}]" />
						<br />
						<input type="checkbox" name="fieldsRequired[${id}]" id="fieldsRequired[${id}]" />
						<label for="fieldsRequired[${id}]"><?php  echo t('Required?'); ?></label>
					</div>
					<div class="designer-content-field-html">
						<label for="fieldSelectOptions[${id}]"><?php  echo t('Dropdown Choices (one per line)'); ?></label><br />
						<textarea rows="6" name="fieldSelectOptions[${id}]" id="fieldSelectOptions[${id}]" class="designer-content-field-select-options"></textarea>
					</div>
					<div class="designer-content-field-html">
						<br />
						<input type="checkbox" name="fieldSelectShowHeaders[${id}]" id="fieldSelectShowHeaders[${id}]" class="designer-content-field-select-header" data-id="${id}" checked="checked" />
						<label for="fieldSelectShowHeaders[${id}]"><?php  echo t('Show List Header?'); ?></label>
						<div class="designer-content-field-select-header-text" data-id="${id}">
							<label for="fieldSelectHeaderTexts[${id}]"><?php  echo t('Header Text'); ?>:</label>
							<input type="text" name="fieldSelectHeaderTexts[${id}]" id="fieldSelectHeaderTexts[${id}]" value="<?php  echo t('--Choose One--'); ?>" />
						</div>
						<table border="0" cellpadding="3" cellspacing="0" class="designer-content-field-select-edit-note"><tr><td valign="top">
							<?php  echo t('NOTE'); ?>:
						</td><td>
							<?php  echo t('After creating this block type, you must edit its "view.php" file to make these choices work!'); ?>
						</td></tr></table>
					</div>
					{{else}}
					<div class="designer-content-field-options">
						<label for="fieldLabels[${id}]"><?php  echo t('Editor Label'); ?></label><br />
						<input type="text" class="designer-content-field-editorlabel" name="fieldLabels[${id}]" id="fieldLabels[${id}]" />
		
						{{if type == 'wysiwyg'}}
							<label for="fieldDefaultContents[${id}]"><?php  echo t('Default HTML Content'); ?></label><br />
							<textarea rows="4" name="fieldDefaultContents[${id}]" id="fieldDefaultContents[${id}]"></textarea>
						{{else}}
							<br />
							<input type="checkbox" name="fieldsRequired[${id}]" id="fieldsRequired[${id}]" />
							<label for="fieldsRequired[${id}]"><?php  echo t('Required?'); ?></label>
						{{/if}}
			
						{{if type == 'textbox'}}
							<br />

							<label for="fieldTextboxMaxlengths[${id}]"><?php  echo t('Maximum Number Of Characters'); ?>:</label>
							<input type="text" name="fieldTextboxMaxlengths[${id}]" id="fieldTextboxMaxlengths[${id}]" size="3" maxlength="5" />
						{{else type == 'image'}}
							<br />
							<table border="0" class="designer-content-field-image-settings"><tr><td nowrap="nowrap" align="right">
								<label for="fieldImageShowAltTexts[${id}]"><?php  echo t('Show Alt Text Field:'); ?></label>
							</td><td nowrap="nowrap">
								<select name="fieldImageShowAltTexts[${id}]" id="fieldImageShowAltTexts[${id}]">
									<option value="0"><?php  echo t('No'); ?></option>
									<option value="1"><?php  echo t('Yes'); ?></option>
								</select>
							</td></tr><tr><td nowrap="nowrap" align="right">
								<label for="fieldImageLinks[${id}]"><?php  echo t('Show Link Field'); ?>:</label>
							</td><td nowrap="nowrap">
								<div class="designer-content-field-image-link">
									<select name="fieldImageLinks[${id}]" id="fieldImageLinks[${id}]" class="designer-content-field-image-link-dropdown" data-id="${id}">
										<option value="0"><?php  echo t('None'); ?></option>
										<option value="1"><?php  echo t('Sitemap Page'); ?></option>
										<option value="2"><?php  echo t('External URL'); ?></option>
									</select>
						
									<span style="display: none;" class="designer-content-field-image-link-options" data-id="${id}">
										&nbsp;&nbsp;&nbsp;
										<input type="checkbox" name="fieldImageLinkTargets[${id}]" id="fieldImageLinkTargets[${id}]" checked="checked" />
										<label for="fieldImageLinkTargets[${id}]"><?php  echo t('Links Open In New Window'); ?></label>
									</span>
								</div>
							</td></tr><tr><td nowrap="nowrap" align="right">
								<label for="fieldImageSizings[${id}]"><?php  echo t('Image Sizing'); ?>:</label>
							</td><td nowrap="nowrap">
								<div class="designer-content-field-image-sizing">
									<select name="fieldImageSizings[${id}]" id="fieldImageSizings[${id}]" class="designer-content-field-image-sizing-dropdown" data-id="${id}">
										<option value="0"><?php  echo t('Keep Original Size'); ?></option>
										<option value="1"><?php  echo t('Resize Proportionally'); ?></option>
										<option value="2"><?php  echo t('Resize+Crop To Fit'); ?></option>
									</select>
				
									<span style="display: none;" class="designer-content-field-image-sizing-options" data-id="${id}">
										&nbsp;&nbsp;
										<label for="fieldImageWidths[${id}]" class="designer-content-field-image-resize-label" data-id="${id}" style="display: none;"><?php  echo t('Max Width'); ?>:</label>
										<label for="fieldImageWidths[${id}]" class="designer-content-field-image-crop-label" data-id="${id}" style="display: none;"><?php  echo t('Target Width'); ?>:</label>
										<input type="text" name="fieldImageWidths[${id}]" id="fieldImageWidths[${id}]" class="designer-content-field-image-width" size="3" maxlength="4" /> px
										&nbsp;&nbsp;
										<label for="fieldImageHeights[${id}]" class="designer-content-field-image-resize-label" data-id="${id}"><?php  echo t('Max Height'); ?>:</label>
										<label for="fieldImageHeights[${id}]" class="designer-content-field-image-crop-label" data-id="${id}"><?php  echo t('Target Height'); ?>:</label>
										<input type="text" name="fieldImageHeights[${id}]" id="fieldImageHeights[${id}]" class="designer-content-field-image-height" size="3" maxlength="4" /> px
									</span>
								</div>
							</td></tr></table>
						{{else type == 'url'}}
							<br />
							<input type="checkbox" name="fieldUrlTargets[${id}]" id="fieldUrlTargets[${id}]" checked="checked" />
							<label for="fieldUrlTargets[${id}]"><?php  echo t('Links Open In New Window'); ?></label>
						{{else type == 'date'}}
							<br />
							<label for="fieldDateFormats[${id}]" data-id="${id}"><?php  echo t('PHP Date Format'); ?>:</label>
							<input type="text" name="fieldDateFormats[${id}]" id="fieldDateFormats[${id}]" value="F jS, Y" style="width:75px;" />
							[<i><a href="<?php  echo t('http://php.net/date#refsect1-function.date-parameters'); ?>" target="_blank"><?php  echo t('Reference'); ?></a></i>]
						{{/if}}
					</div>
		
					<div class="designer-content-field-html">
						<label for="fieldPrefixes[${id}]"><?php  echo t('Wrapper HTML Open'); ?> <i>(&lt;div class="abc"&gt;)</i></label><br />
						<textarea rows="3" name="fieldPrefixes[${id}]" id="fieldPrefixes[${id}]"></textarea>
					</div>
					<div class="designer-content-field-html">
						<label for="fieldSuffixes[${id}]"><?php  echo t('Wrapper HTML Close'); ?> <i>(&lt;/div&gt;)</i></label><br />
						<textarea rows="3" name="fieldSuffixes[${id}]" id="fieldSuffixes[${id}]"></textarea>
					</div>
					{{/if}}
		        </div>
			    </script>
			</div>

			<div id="designer-content-fields-add">
				<table border="0" cellpadding="0" cellspacing="0"><tr><td valign="top" nowrap="nowrap">
					<h2><?php  echo t('Add Field');?>:&nbsp;</h2>
				</td><td>
					<div id="add-field-types">
					</div>
					<script id="add-field-types-template" type="text/x-jQuery-tmpl">
						[<a href="#" class="add-field-type" data-type="static"><?php  echo s2nb(t('Static HTML')); ?></a>]
						&nbsp;&nbsp;
						[<a href="#" class="add-field-type" data-type="textbox"><?php  echo s2nb(t('Text Box')); ?></a>]
						&nbsp;&nbsp;
						[<a href="#" class="add-field-type" data-type="textarea"><?php  echo s2nb(t('Text Area')); ?></a>]
						&nbsp;&nbsp;
						[<a href="#" class="add-field-type" data-type="image"><?php  echo s2nb(t('Image')); ?></a>]
						&nbsp;&nbsp;
						[<a href="#" class="add-field-type" data-type="file"><?php  echo s2nb(t('File Download')); ?></a>]
						&nbsp;&nbsp;
						[<a href="#" class="add-field-type" data-type="link"><?php  echo s2nb(t('Page Link')); ?></a>]
						&nbsp;&nbsp;
						[<a href="#" class="add-field-type" data-type="url"><?php  echo s2nb(t('External URL')); ?></a>]
						&nbsp;&nbsp;
						[<a href="#" class="add-field-type" data-type="date"><?php  echo s2nb(t('Date Picker')); ?></a>]
						&nbsp;&nbsp;
						[<a href="#" class="add-field-type" data-type="select"><?php  echo s2nb(t('Dropdown List')); ?></a>]
						&nbsp;&nbsp;
						[<a href="#" class="add-field-type" data-type="wysiwyg"><?php  echo s2nb(t('WYSIWYG Editor')); ?></a>]
					</script>
				</td></tr></table>
			</div>

			<hr />

			<?php  if ($can_write) { ?>
				<div id="designer-content-submit-wrapper">
					<div id="designer-content-submit">
						<button class="btn btn-primary">
							<i class="icon-wrench icon-white"></i> <?php  echo t('Create The Block'); ?>
						</button>
					</div>
					<div id="designer-content-submit-loading" style="display: none;">
						<button class="btn btn-primary active">
							<i class="icon-wrench icon-white"></i> <?php  echo t('Processing...'); ?>
						</button>
					</div>
				</div>
			<?php } ?>

			<div style="clear: both;"></div>

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