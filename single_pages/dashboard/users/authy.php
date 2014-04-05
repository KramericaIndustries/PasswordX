<?php
/**
 * Dashboard config view
 * c5authy
 * @author: Stefan Fodor
 * Built with love by Stefan Fodor @ 2014
 */
defined('C5_EXECUTE') or die("Access Denied.");

//Set the header and dashboard theme
$title=t('Authy Configuration');
echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper($title, false, 'span10 offset1', false);
?>

<form method="post" class="form-horizontal" action="<?php  echo $this->action('update_config') ?>">
    <div class="ccm-pane-body">
        <?php  echo $this->controller->token->output('update_authy_config')?>
        <fieldset>
            <legend style="margin-bottom: 0px"><?php  echo t('Authentication Type')?></legend>
            <div class="control-group">
                <div class="controls">
                    <label class="radio">
                        <input type="radio" name="AUTH_TYPE" value="0" <?php if($authy_type == "0") echo "checked"; ?> />
                        <span><strong><?php echo t('Default')?></strong> - <?php  echo t('default login system')?></span>
                    </label>
                </div>
                <div class="controls">
                    <label class="radio">
                        <input type="radio" name="AUTH_TYPE" value="1" <?php if($authy_type == "1") echo "checked"; ?> />
                        <span><strong><?php echo t('One Time Password')?></strong> - <?php  echo t('the user will use the Authy generated token as password')?></span>
                    </label>
                </div>
                <div class="controls">
                    <label class="radio">
                        <input type="radio" name="AUTH_TYPE" value="2" <?php if($authy_type =="2") echo "checked"; ?> />
                        <span><strong><?php echo t('Two Factor Authentication')?></strong> - <?php  echo t('requires a valid password and a valid Authy token')?></span>
                    </label>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend style="margin-bottom: 0px"><?php  echo t('Authy Mode')?></legend>
            <div class="control-group">
                <div class="controls">
                    <label class="radio">
                        <input type="radio" name="AUTHY_SERVER" value="0" <?php if($authy_server_production == "0") echo "checked"; ?>/>
                        <span><strong><?php echo t('Sandbox')?></strong> - <?php  echo t('good for development')?></span>
                    </label>
                </div>
                <div class="controls">
                    <label class="radio">
                        <input type="radio" name="AUTHY_SERVER" value="1"  <?php if($authy_server_production == "1") echo "checked"; ?> />
                        <span><strong><?php echo t('Production')?></strong> - <?php  echo t('required on live sites')?></span>
                    </label>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend style="margin-bottom: 0px"><?php  echo t('SMS Tokens')?></legend>
            <div class="control-group">
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
                        <input type="radio" name="AUTHY_SMS" value="2"  <?php if($authy_sms_tokens == "2") echo "checked"; ?> />
                        <span><strong><?php echo t('Enabled')?></strong> - <?php  echo t('any user can request an SMS token')?></span>
                    </label>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend style="margin-bottom: 0px"><?php  echo t('Authy API Key')?></legend>
            <div class="control-group">
                <div class="controls">
                    <input type="text" name="AUTHY_KEY" value="<?php echo $authy_api_key ?>" style="height: 20px; width: 250px;"/>
                </div>
            </div>

        </fieldset>
    </div>
    <div class="ccm-pane-footer">
        <input type="submit" class="btn ccm-button-v2 primary ccm-button-v2-right" value="<?php echo t('Save'); ?>">
    </div>
</form>
<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);?>