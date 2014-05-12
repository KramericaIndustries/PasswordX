<?php  defined('C5_EXECUTE') or die("Access Denied.");
?>

<style>
.form-horizontal .form-group {
	margin-right: 0px!important;
}
</style>

<div class="form-group">
	<label for="field_1_textbox_text" class="col-lg-2 control-label">fsdf</label>
		<div class="col-lg-10">
			<?php  echo $form->text('field_1_textbox_text', $field_1_textbox_text, array('style' => '', 'placeholder' => 'fsdf...', 'class'=>'form-control', 'autocomplete' => 'off')); ?>
		</div>
</div>
<div class="form-group">
	<label for="field_2_textbox_text" class="col-lg-2 control-label">fdsfds</label>
		<div class="col-lg-10">
			<?php  echo $form->password('field_2_textbox_text', $field_2_textbox_text, array('style' => 'width: 45%; display: inline;' , 'placeholder' => 'fdsfds...', 'class'=>'form-control', 'autocomplete' => 'off' )); ?>
			<input id="miror_field_2_textbox_text" type="text" class="form-control" style="width: 45%; display: none;" value="" />
			<button class="btn btn-primary sugest_pass" data-target="field_2_textbox_text"> Suggest a password</button>
			<button class="btn btn-danger clear_view" data-target="field_2_textbox_text"> Clearview the password</button>
		</div>
</div>
<div class="form-group">
	<label for="field_3_textarea_text" class="col-lg-2 control-label">fdsfds</label>
		<div class="col-lg-10">
	<textarea class="form-control" placeholder="fdsfds..." id="field_3_textarea_text" name="field_3_textarea_text" rows="5"><?php  echo $field_3_textarea_text; ?></textarea>
		</div>
</div>

