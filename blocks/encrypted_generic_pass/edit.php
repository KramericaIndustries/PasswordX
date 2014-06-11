<?php  
/**
 * Edit view for the Generic Password Block
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
    <label for="inputEmail1" class="col-lg-2 control-label">Title <i>(optional)</i></label>
    <div class="col-lg-10">
      <?php  echo $form->text('field_1_textbox_text', $field_1_textbox_text, array('style' => '', 'placeholder' => 'Title...', 'class'=>"form-control", "autocomplete" => "off" )); ?>
    </div>
  </div>


   <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Username</label>
    <div class="col-lg-10">
      <?php  echo $form->text('field_2_textbox_text', $field_2_textbox_text, array('style' => '', 'placeholder' => 'Username...', 'class'=>"form-control", "autocomplete" => "off")); ?>
    </div>
  </div>
  
   <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Password</label>
    <div class="col-lg-10">
      <?php 
		if ($handheld) {
		 echo $form->text('field_3_textbox_text', $field_3_textbox_text, array('style' => 'width: 45%; display: inline;','placeholder' => 'Password...', 'class'=>"form-control", "autocomplete" => "off"));
		} else {
		 echo $form->password('field_3_textbox_text', $field_3_textbox_text, array('style' => 'width: 45%; display: inline;','placeholder' => 'Password...', 'class'=>"form-control", "autocomplete" => "off")); 
		}
		?>
		<input id="pass_mirror" type="text" class="form-control" style="width: 45%; display: none;" value="" />
		<button class="btn btn-primary" id="sugest_pass"> Suggest a password</button>
		<button class="btn btn-danger clear_view" id="clear_view"> Clearview the password</button>
	</div>
	
		<div class="clearfix">
		 <div class="col-lg-2 control-label"></div>
		 <div class="col-lg-10">
		  <div  id="password_input">
		   <div id="pwstrength_viewport_progress" style="width: 45%;" class="pull-left" ></div><div class="pull-left">&nbsp;&nbsp;</div> 
		   <div id="pwstrength_viewport_verdict" class="pull-left"></div> 
		   <a href="https://tech.dropbox.com/2012/04/zxcvbn-realistic-password-strength-estimation/" target="_blank">&nbsp;Why?</a>
		  </div>
		 </div>
		</div>		
	
  </div>
  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Notes <i>(optional)</i></label>
    <div class="col-lg-10">
      <textarea placeholder="Notes..." class="form-control" id="field_4_textarea_text" name="field_4_textarea_text" rows="5" style=""><?php  echo $field_4_textarea_text; ?></textarea>
    </div>
  </div>


  <div class="form-group" style="display: none">
    <label for="inputEmail1" class="col-lg-2 control-label">Block Type</label>
    <div class="col-lg-10">
      	<?php 
			$options = array(
				'0' => 'Info',
				'1' => 'Warning',
				'2' => 'Danger',
			);
			echo $form->select('field_5_select_value', $options, $field_5_select_value,array('style' => 'width: 18%;', 'class'=>"form-control"));
		?>
    </div>
  </div>
  
	<!-- Password strength meter for Bootstrap 
		https://github.com/ablanco/jquery.pwstrength.bootstrap
		https://tech.dropbox.com/2012/04/zxcvbn-realistic-password-strength-estimation/
	-->	

<script type="text/javascript" src="/js/zxcvbn.js"></script>
<script type="text/javascript" src="/js/pwstrength.js"></script>
<script type="text/javascript">
        $(function () {
             var options = {};
             options.ui = {
				bootstrap2: false,
                container: "#password_input",
                viewports: {
                    progress: "#pwstrength_viewport_progress",
                    verdict: "#pwstrength_viewport_verdict"
                }
            };
            options.common = {
				minChar: 5,
                onLoad: function () {
				 $('.password-verdict').html("Password strength.");
                },
                zxcvbn: true
            };

            $('#field_3_textbox_text').val('');
            $('#field_3_textbox_text').pwstrength(options);
        });
    </script>		
  