<?php
/**
 * Configuration view for 2 factor auth
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied.");

//Set the header and dashboard theme
$title=t('Two Factor Configuration');
echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper($title, false, 'span10 offset1', false);
?>

<form method="post" class="form-horizontal" action="<?php  echo $this->action('update_config') ?>">
    <div class="ccm-pane-body">
        <?php  echo $this->controller->token->output('update_2fa_config')?>
        <fieldset>
            <legend style="margin-bottom: 0px"><?php  echo t('Two Factor Authentication Method')?></legend>
            <div class="control-group">
                <div class="controls">
                    <label class="radio">
                        <input type="radio" name="TWO_FACTOR_METHOD" value="no_2factor" <?php if($TWO_FACTOR_METHOD == "no_2factor") echo "checked"; ?> />
                        <span><strong><?php echo t('Disabled')?></strong> - <?php  echo t('default login system')?></span>
                    </label>
                </div>
                <div class="controls">
                    <label class="radio">
                        <input type="radio" name="TWO_FACTOR_METHOD" value="authy" <?php if($TWO_FACTOR_METHOD == "authy") echo "checked"; ?> />
                        <span><strong><?php echo t('Authy')?></strong> - <?php echo t('Use Authy')?></span>
                    </label>
                </div>
                <div class="controls">
                    <label class="radio">
                        <input type="radio" name="TWO_FACTOR_METHOD" value="google" <?php if($TWO_FACTOR_METHOD =="google") echo "checked"; ?> />
                        <span><strong><?php echo t('Google Authentication')?></strong> - <?php echo t('Use Google Authentication')?></span>
                    </label>
                </div>
            </div>
        </fieldset>
        
        <div id="2fa-options" style="<?php echo ($TWO_FACTOR_METHOD == "no_2factor" ? "display:none;" : ""); ?>">
	        <fieldset>
	            <legend style="margin-bottom: 0px"><?php  echo t('Authentication Type')?></legend>
	            <div class="control-group">
	                <div class="controls">
	                    <label class="radio">
	                        <input type="radio" name="AUTH_FACTORS_REQUIRED" value="1" <?php if($AUTH_FACTORS_REQUIRED == "1") echo "checked"; ?> />
	                        <span><strong><?php echo t('One Time Password')?></strong> - <?php  echo t('the user will use the generated token as password')?></span>
	                    </label>
	                </div>
	                <div class="controls">
	                    <label class="radio">
	                        <input type="radio" name="AUTH_FACTORS_REQUIRED" value="2" <?php if($AUTH_FACTORS_REQUIRED =="2") echo "checked"; ?> />
	                        <span><strong><?php echo t('Two Factor Authentication')?></strong> - <?php  echo t('requires a valid password and a valid token')?></span>
	                    </label>
	                </div>
	            </div>
	        </fieldset>
		</div>
		
		<div id="authy-options" style="<?php echo ($TWO_FACTOR_METHOD == "google" ? "display:none;" : ""); ?>">
	        <fieldset>
	            <legend style="margin-bottom: 0px"><?php  echo t('Authy Configuration')?></legend>
	            <div class="control-group">
	            	<label class="control-label"><?php  echo t('SMS Tokens')?></label>
	                <div class="controls">
	                    <label class="radio">
	                        <input type="radio" name="AUTHY_SMS" value="0" <?php if($authy_sms_tokens == "0") echo "checked"; ?>/>
	                        <span><strong><?php echo t('Disabled')?></strong> - <?php  echo t('SMS token are not allowed')?></span>
	                    </label>
	                </div>
	                <div class="controls">
	                    <label class="radio">
	                        <input type="radio" name="AUTHY_SMS" value="1"  <?php if($authy_sms_tokens == "1") echo "checked"; ?> />
	                        <span><strong><?php echo t('Limited')?></strong> - <?php  echo t('available only for users that do not have a smartphone')?></span>
	                    </label>
	                </div>
	                <div class="controls">
	                    <label class="radio">
	                        <input type="radio" name="AUTHY_SMS" value="2"  <?php echo (!$authy_sms_tokens || $authy_sms_tokens == "2" ? "checked" : ""); ?> />
	                        <span><strong><?php echo t('Enabled')?></strong> - <?php  echo t('any user can request an SMS token')?></span>
	                    </label>
	                </div>
	            </div>

	            <div class="control-group">
	            	<label class="control-label"><?php  echo t('API Key')?></label>
	                <div class="controls">
	                    <input type="text" name="AUTHY_KEY" value="<?php echo $authy_api_key ?>" style="height: 20px; width: 250px;"/>
	                </div>
	            </div>
	        </fieldset>
        </div>
        
        <div id="google-options" <?php echo ($TWO_FACTOR_METHOD == "authy" ? "display:none;" : ""); ?>>
        	<fieldset>
	            <legend style="margin-bottom: 0px"><?php  echo t('Google Configuration')?></legend>
	          	
	          	<?php if($show_secret_warning) { ?>
		          	<div class="alert alert-warning"><i class="icon-warning-sign"></i> 
					 <strong><?php echo t('You do not have a Google Authentication Secret setup for all the users in the system.'); ?></strong>
					 <p><?php echo t('Be sure to generate one in Users management after saving these changes, otherwise users may end up not having access to the system.')?></p>
					</div>
	            <?php } ?>
	            
	            <div class="control-group">
	            	<label class="control-label"><?php  echo t('Valid time slice')?></label>
	                <div class="controls">
	                    <label class="select">
	                    	<select name="GA_TIME_SLICE">
	                    		<option value="30" <?php echo ($GA_TIME_SLICE == "30" ? "selected" : "")?>>30 <?php  echo t('seconds')?></option>
	                    		<option value="45" <?php echo ($GA_TIME_SLICE == "45" ? "selected" : "")?>>45 <?php  echo t('seconds')?></option>
	                    		<option value="60" <?php echo ($GA_TIME_SLICE == "60" ? "selected" : "")?>>60 <?php  echo t('seconds')?></option>
	                    	</select>
	                    </label>
	                </div>
	            </div>
	        </fieldset>
        </div>
        
    </div>
    <div class="ccm-pane-footer">
        <input type="submit" class="btn ccm-button-v2 primary ccm-button-v2-right" value="<?php echo t('Save'); ?>">
    </div>
</form>

<script type="text/javascript">
$(function(){
	
	//show relevant config
	$("[name='TWO_FACTOR_METHOD']").change(function(){

		if( $(this).val() == "no_2factor" ) {

			$("#google-options").hide();
			$("#authy-options").hide();
			$("#2fa-options").hide();

		}else if($(this).val() == "authy") {

			$("#google-options").hide();
			$("#authy-options").show();
			$("#2fa-options").show();
				
		}else {

			$("#authy-options").hide();
			$("#google-options").show();
			$("#2fa-options").show();
		
		}

	});
	
});
</script>
<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);?>