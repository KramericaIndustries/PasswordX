<?php  
/**
 * Edit for VHost Password Block
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied.");


Loader::library('3rdparty/mobile_detect');
$md = new Mobile_Detect();
$handheld = $md->isMobile() || $md->isTablet();

?>
<style>
.form-horizontal .form-group {
	margin-right: 0px!important;
}
</style>

   <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Hostname</label>
    <div class="col-lg-10">
      <?php  echo $form->text('field_1_textbox_text', $field_1_textbox_text, array('style' => '', 'placeholder' => 'Title...', 'class'=>"form-control", "autocomplete" => "off")); ?>
    </div>
  </div>


   <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">FTP Username</label>
    <div class="col-lg-10">
      <?php  echo $form->text('field_2_textbox_text', $field_2_textbox_text, array('style' => '', 'placeholder' => 'FTP Username...', 'class'=>"form-control", "autocomplete" => "off")); ?>
    </div>
  </div>
  
   <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">FTP Password</label>
    <div class="col-lg-10">
      <?php 
		if ($handheld) {
		 echo $form->text('field_3_textbox_text', $field_3_textbox_text, array('style' => 'width: 45%; display: inline;','placeholder' => 'Password...', 'class'=>"form-control", "autocomplete" => "off"));
		} else {
		 echo $form->password('field_3_textbox_text', $field_3_textbox_text, array('style' => 'width: 45%; display: inline;','placeholder' => 'Password...', 'class'=>"form-control", "autocomplete" => "off")); 
		}
		?>	
		<input id="miror_field_3_textbox_text" type="text" class="form-control" style="width: 45%; display: none;" value="" />
		<button class="btn btn-primary sugest_pass" data-target="field_3_textbox_text"> Suggest a password</button>
		<button class="btn btn-danger clear_view" data-target="field_3_textbox_text"> Clearview the password</button>
	</div>
	
  </div>
  
  
    <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">SSH Username</label>
    <div class="col-lg-10">
      <?php  echo $form->text('field_4_textbox_text', $field_4_textbox_text, array('style' => '', 'placeholder' => 'SSH Username...', 'class'=>"form-control", "autocomplete" => "off")); ?>
    </div>
  </div>
  
   <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">SSH Password</label>
    <div class="col-lg-10">
      <?php 
		if ($handheld) {
		 echo $form->text('field_5_textbox_text', $field_5_textbox_text, array('style' => 'width: 45%; display: inline;','placeholder' => 'Password...', 'class'=>"form-control", "autocomplete" => "off"));
		} else {
		 echo $form->password('field_5_textbox_text', $field_5_textbox_text, array('style' => 'width: 45%; display: inline;','placeholder' => 'Password...', 'class'=>"form-control", "autocomplete" => "off")); 
		}
		?>	
		<input id="miror_field_5_textbox_text" type="text" class="form-control" style="width: 45%; display: none;" value="" />
		<button class="btn btn-primary sugest_pass" data-target="field_5_textbox_text"> Suggest a password</button>
		<button class="btn btn-danger clear_view" data-target="field_5_textbox_text"> Clearview the password</button>
	</div>
	
  </div>
  

    <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">DB Username</label>
    <div class="col-lg-10">
      <?php  echo $form->text('field_6_textbox_text', $field_6_textbox_text, array('style' => '', 'placeholder' => 'DB Username...', 'class'=>"form-control", "autocomplete" => "off")); ?>
    </div>
  </div>
  
   <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">DB Password</label>
    <div class="col-lg-10">
      <?php 
		if ($handheld) {
		 echo $form->text('field_7_textbox_text', $field_7_textbox_text, array('style' => 'width: 45%; display: inline;','placeholder' => 'Password...', 'class'=>"form-control", "autocomplete" => "off"));
		} else {
		 echo $form->password('field_7_textbox_text', $field_7_textbox_text, array('style' => 'width: 45%; display: inline;','placeholder' => 'Password...', 'class'=>"form-control", "autocomplete" => "off")); 
		}
		?>		
		<input id="miror_field_7_textbox_text" type="text" class="form-control" style="width: 45%; display: none;" value="" />
		<button class="btn btn-primary sugest_pass" data-target="field_7_textbox_text"> Suggest a password</button>
		<button class="btn btn-danger clear_view" data-target="field_7_textbox_text"> Clearview the password</button>
	</div>
	
  </div>  
  
   <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">DB names</label>
    <div class="col-lg-10">
      <?php  echo $form->text('field_8_textbox_text', $field_8_textbox_text, array('style' => '', 'placeholder' => 'DB Username...', 'class'=>"form-control", "autocomplete" => "off")); ?>
    </div>
  </div>
  
  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Notes <i>(optional)</i></label>
    <div class="col-lg-10">
      <textarea placeholder="Notes..." class="form-control" id="field_9_textarea_text" name="field_9_textarea_text" rows="5"><?php  echo $field_9_textarea_text; ?></textarea>
    </div>
  </div>
