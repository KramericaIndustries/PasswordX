<?php  defined('C5_EXECUTE') or die("Access Denied.");
Loader::element('editor_config');
?>

<style type="text/css" media="screen">
	.ccm-block-field-group h2 { margin-bottom: 5px; }
	.ccm-block-field-group td { vertical-align: middle; }
</style>

<div class="ccm-block-field-group">
	<h2>a</h2>
	<?php  echo $form->text('field_1_textbox_text', $field_1_textbox_text, array('style' => 'width: 95%;')); ?>
</div>

<div class="ccm-block-field-group">
	<h2>c</h2>
	<textarea id="field_3_textarea_text" name="field_3_textarea_text" rows="5" style="width: 95%;"><?php  echo $field_3_textarea_text; ?></textarea>
</div>

<div class="ccm-block-field-group">
	<h2>d</h2>
	<?php  Loader::element('editor_controls'); ?>
	<textarea id="field_4_wysiwyg_content" name="field_4_wysiwyg_content" class="ccm-advanced-editor"><?php  echo $field_4_wysiwyg_content; ?></textarea>
</div>


