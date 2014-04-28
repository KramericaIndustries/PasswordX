<?php  defined('C5_EXECUTE') or die("Access Denied.");
?>

<style>
.form-horizontal .form-group {
	margin-right: 0px!important;
}
</style>

<div class="form-group">
	<label for="field_1_textbox_text" class="col-lg-2 control-label">T1</label>
		<div class="col-lg-10">
			<?php  echo $form->text('field_1_textbox_text', $field_1_textbox_text, array('style' => '', 'placeholder' => 'T1...', 'class'=>'form-control', 'autocomplete' => 'off')); ?>
		</div>
	</div>
<div class="form-group">
	<label for="field_2_textbox_text" class="col-lg-2 control-label">Test 2</label>
		<div class="col-lg-10">
			<?php  echo $form->text('field_2_textbox_text', $field_2_textbox_text, array('style' => '', 'placeholder' => 'Test 2...', 'class'=>'form-control', 'autocomplete' => 'off')); ?>
		</div>
	</div>
<div class="form-group">
	<label for="field_3_textbox_text" class="col-lg-2 control-label">teeest 3</label>
		<div class="col-lg-10">
			<?php  echo $form->text('field_3_textbox_text', $field_3_textbox_text, array('style' => '', 'placeholder' => 'teeest 3...', 'class'=>'form-control', 'autocomplete' => 'off')); ?>
		</div>
	</div>

