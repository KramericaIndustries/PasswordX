<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php  Loader::library('authentication/open_id');?>
<?php  $form = Loader::helper('form'); ?>
<style>
	div#ccm-logo {
		border-top: none;
	}
	img#ccm-logo {
		width: 40px;
		height: 40px;
		margin-top: 4px;
	}
</style>
<script type="text/javascript">
    $(function() {
        $("input[name=uName]").focus();

        //logic for requesting SMS tokens
       $("#request_sms").click(function(){

           phone = prompt("Please enter your phone number");

           if (phone!=null) {
               $.ajax({
                   url : "<?php echo $this->action('request_sms') ?>",
                   type : 'POST',
                   data : { phone: phone },
                   dataType : 'json',
                   beforeSend : function(jqXHR, settings ) {
                       $("#request_sms").html( "(<?php echo t('Requesting SMS token...') ?>)" );
                   },
                   error: function(jqXHR, textStatus, errorThrown) {
                       // If request fails
                       alert("Internal error!");
                   },
                   success: function(data, textStatus, jqXHR) {
                       if(data.status !== "OK") {
                           alert(data.msg);
                       } else {
                           $("#request_sms").html( "(<?php echo t('SMS sent successfully!') ?>)" );
                       }
                   }
               });
           }
       });
    });
</script>

<?php  if (isset($intro_msg)) { ?>
    <div class="alert-message block-message success"><p><?php echo $intro_msg?></p></div>
<?php  } ?>

<div class="row">
    <div class="span10 offset1">
        <div class="page-header">
            <h1><?php echo t('Sign in to %s', SITE)?></h1>
        </div>
    </div>
</div>

<?php  if( $passwordChanged ){ ?>

    <div class="block-message info alert-message"><p><?php echo t('Password changed.  Please login to continue. ') ?></p></div>

<?php  } ?>

<?php  if($changePasswordForm){ ?>

    <p><?php echo t('Enter your new password below.') ?></p>

    <div class="ccm-form">

        <form method="post" action="<?php echo $this->url( '/login', 'change_password', $uHash )?>">

            <div class="control-group">
                <label for="uPassword" class="control-label"><?php echo t('New Password')?></label>
                <div class="controls">
                    <input tabindex="1" type="password" name="uPassword" id="uPassword" class="ccm-input-text">
                </div>
            </div>
            <div class="control-group">
                <label for="uPasswordConfirm"  class="control-label"><?php echo t('Confirm Password')?></label>
                <div class="controls">
                    <input tabindex="2" type="password" name="uPasswordConfirm" id="uPasswordConfirm" class="ccm-input-text">
                </div>
            </div>
            <div class="control-group">
                <label for="uPasswordConfirm"  class="control-label">
                        <?php echo t('Token')?>
                        <a tabindex="4" href="javascript:void(0);" id="request_sms" style="outline: none;">(<?php echo t('Request SMS token') ?>)</a>
                </label>
                <?php if($authy_enabled){ ?>
                    <div class="controls">
                        <input tabindex="3" type="text" name="uToken" id="uToken" maxlength="7" class="ccm-input-text">
                    </div>
                <?php } ?>
            </div>

            <div class="actions">
                <?php echo $form->submit('submit', t('Sign In') . ' &gt;')?>
            </div>
        </form>

    </div>

<?php  }elseif($validated) { ?>

    <h3><?php echo t('Email Address Verified')?></h3>

    <div class="success alert-message block-message">
        <p>
            <?php echo t('The email address <b>%s</b> has been verified and you are now a fully validated member of this website.', $uEmail)?>
        </p>
        <div class="alert-actions"><a class="btn small" href="<?php echo $this->url('/')?>"><?php echo t('Continue to Site')?></a></div>
    </div>


<?php  } else if (isset($_SESSION['uOpenIDError']) && isset($_SESSION['uOpenIDRequested'])) { ?>

    <div class="ccm-form">

        <?php  switch($_SESSION['uOpenIDError']) {
            case OpenIDAuth::E_REGISTRATION_EMAIL_INCOMPLETE: ?>

                <form method="post" action="<?php echo $this->url('/login', 'complete_openid_email')?>">
                    <p><?php echo t('To complete the signup process, you must provide a valid email address.')?></p>
                    <label for="uEmail"><?php echo t('Email Address')?></label><br/>
                    <?php echo $form->text('uEmail')?>

                    <div class="ccm-button">
                        <?php echo $form->submit('submit', t('Sign In') . ' &gt;')?>
                    </div>
                </form>

                <?php  break;
            case OpenIDAuth::E_REGISTRATION_EMAIL_EXISTS:

                $ui = UserInfo::getByID($_SESSION['uOpenIDExistingUser']);

                ?>

                <form method="post" action="<?php echo $this->url('/login', 'do_login')?>">
                    <p><?php echo t('The OpenID account returned an email address already registered on this site. To join this OpenID to the existing user account, login below:')?></p>
                    <label for="uEmail"><?php echo t('Email Address')?></label><br/>
                    <div><strong><?php echo $ui->getUserEmail()?></strong></div>
                    <br/>

                    <div>
                        <label for="uName"><?php  if (USER_REGISTRATION_WITH_EMAIL_ADDRESS == true) { ?>
                                <?php echo t('Email Address')?>
                            <?php  } else { ?>
                                <?php echo t('Username')?>
                            <?php  } ?></label><br/>
                        <input type="text" name="uName" id="uName" <?php echo  (isset($uName)?'value="'.$uName.'"':'');?> class="ccm-input-text">
                    </div>			<div>

                        <label for="uPassword"><?php echo t('Password')?></label><br/>
                        <input type="password" name="uPassword" id="uPassword" class="ccm-input-text">
                    </div>

                    <div class="ccm-button">
                        <?php echo $form->submit('submit', t('Sign In') . ' &gt;')?>
                    </div>
                </form>

                <?php  break;

        }
        ?>

    </div>

<?php  } else if ($invalidRegistrationFields == true) { ?>

    <div class="ccm-form">

        <p><?php echo t('You must provide the following information before you may login.')?></p>

        <form method="post" action="<?php echo $this->url('/login', 'do_login')?>">
            <?php
            $attribs = UserAttributeKey::getRegistrationList();
            $af = Loader::helper('form/attribute');

            $i = 0;
            foreach($unfilledAttributes as $ak) {
                if ($i > 0) {
                    print '<br/><br/>';
                }
                print $af->display($ak, $ak->isAttributeKeyRequiredOnRegister());
                $i++;
            }
            ?>

            <?php echo $form->hidden('uName', Loader::helper('text')->entities($_POST['uName']))?>
            <?php echo $form->hidden('uPassword', Loader::helper('text')->entities($_POST['uPassword']))?>
            <?php echo $form->hidden('uOpenID', $uOpenID)?>
            <?php echo $form->hidden('completePartialProfile', true)?>

            <div class="ccm-button">
                <?php echo $form->submit('submit', t('Sign In'))?>
                <?php echo $form->hidden('rcID', $rcID); ?>
            </div>

        </form>
    </div>

<?php  } else { ?>

    <form method="post" action="<?php echo $this->url('/login', 'do_login')?>" class="form-horizontal">

        <div class="row">
            <div class="span12 offset1">

                        <fieldset>

							
							<?php  if (isset($locales) && is_array($locales) && count($locales) > 0) { ?>
                                <div class="control-group">
                                    <label for="USER_LOCALE" class="control-label"><?php echo t('Language')?></label>
                                    <div class="controls"><?php echo $form->select('USER_LOCALE', $locales)?></div>
                                </div>
                            <?php  } ?>

                            <div class="control-group">
							<label class="control-label">
							 <?php echo t('Remember Me')?>
							</label>
							<div class="controls">
                                <label class="checkbox"><?php echo $form->checkbox('uMaintainLogin', 1)?> <span><?php echo t('Stay signed in for 2 weeks.')?> <strong>Warning:</strong> Do NOT check this box if you are logging in from a public location</span></label>
							</div>	
								
                            </div>
							
                            <?php  $rcID = isset($_REQUEST['rcID']) ? Loader::helper('text')->entities($_REQUEST['rcID']) : $rcID; ?>
                            <input type="hidden" name="rcID" value="<?php echo $rcID?>" />
							
							

                            <div class="control-group">

                                <label for="uName" class="control-label"><?php  if (USER_REGISTRATION_WITH_EMAIL_ADDRESS == true) { ?>
                                        <?php echo t('Email Address')?>
                                    <?php  } else { ?>
                                        <?php echo t('Username')?>
                                    <?php  } ?></label>
                                <div class="controls">
                                    <input tabindex="1" type="text" name="uName" id="uName" <?php echo  (isset($uName)?'value="'.$uName.'"':'');?> class="ccm-input-text">
                                </div>

                            </div>
                            <?php if( !$otp ) { ?>
                                <div class="control-group">

                                    <label for="uPassword" class="control-label"><?php echo t('Password')?></label>

                                    <div class="controls">
                                        <input tabindex="2" type="password" name="uPassword" id="uPassword" class="ccm-input-text" />
                                    </div>

                                </div>
                            <?php } ?>
                            <?php if($authy_enabled){ ?>
                                <div class="control-group">

                                    <label for="uPassword" class="control-label">
                                           <?php echo t('Token')?>
                                            <?php if( $sms ) { ?>
                                                <a tabindex="4" href="javascript:void(0);" id="request_sms" style="font-size: 10px; display:block; margin-top: -5px; outline: none;">(<?php echo t('Request SMS token') ?>)</a>
                                            <?php } ?>
                                    </label>

                                    <div class="controls">
                                        <input tabindex="3" type="text" name="uToken" id="uToken" maxlength="7" class="ccm-input-text" />
                                    </div>

                                </div>
                            <?php } ?>
                        </fieldset>

                        <?php  if (OpenIDAuth::isEnabled()) { ?>
                            <fieldset>

                                <legend><?php echo t('OpenID')?></legend>

                                <div class="control-group">
                                    <label for="uOpenID" class="control-label"><?php echo t('Login with OpenID')?>:</label>
                                    <div class="controls">
                                        <input type="text" name="uOpenID" id="uOpenID" <?php echo  (isset($uOpenID)?'value="'.$uOpenID.'"':'');?> class="ccm-input-openid">
                                    </div>
                                </div>
                            </fieldset>
                        <?php  } ?>

					
					
					<div class="row">
                    <div class="span10">
                        <div class="actions">
                            <?php echo $form->submit('submit', t('Sign In') . ' &gt;', array('class' => 'primary'))?>
                        </div>
                    </div>
					</div>
				
            </div>
        </div>
    </form>
	
	<?php
	 //Check for SSL Encrypted
	 if ((true) && (!SUPPRESS_SSL_WARNING)) { //Connection is insecure
	 ?>
	 <div class="row">
       <div class="span10 offset1">
	   <h3 style="color: #990000;"><?php echo t('This connection is not secure. Please set up SSL encryption.')?></h3>
	    <p>This connection is not secure at the moment as you are connecting through plain HTTP. This means you are susceptible to <a href="http://en.wikipedia.org/wiki/Man-in-the-middle_attack" target="_blank">man-in-the-middle attacks</a>. </p>
		
		<p>How you set up SSL depends on what your webserver setup is. You do not need to buy an SSL certificate, as an unsigned certificate you issue yourself is just as secure as an unsigned one. However, users will see a browser warning if you serve an unsigned certificate.</p>
	
		<p>If you need easy and hassle-free HTTPS we recommend using <a href="https://www.cloudflare.com/" target="_blank">Cloudflare</a> as your DNS provider, as they have a very affordable turnkey SSL encryption (along with a host of other benefits such as CDN, DDOS attack mitigation and more) in their paid Pro plans.</p>
		
		<div class="actions">
		<p><i class="icon-info-sign"></i> If this installation is running on a secure network, the administrator can disable this warning message by setting SUPPRESS_SSL_WARNING to true in config/site.php</p>
		</div>
		
	   </div>
	 </div>
	 
	 <?php } ?>	
	
	<?php
	 //Check for Two-Factor enabled
	 if ((true) && (!SUPPRESS_TWOFACTOR_WARNING)) { //Two-factor is disabled atm
	 ?>
	 <div class="row">
       <div class="span10 offset1">
	   <h3 style="color: #990000;"><?php echo t('Two-Factor Authentication is disabled!')?></h3>

	   <p>If this system is publicly accessible on the web, it is strongly recommended that you enable two-factor authentication. If you do not know what two-factor authentication is, <a href="http://en.wikipedia.org/wiki/Two-step_verification" target="_blank">read more at Wikipedia.</a> You should only turn off two-factor authentication if your installation is behind a firewall and only accessible on a LAN or through a VPN.</p>
	   
	   <p>You can configure Two-Factor authentication in the Dashboard after logging in</p>
	   
	   <div class="actions">
	   	<p><i class="icon-info-sign"></i> If this installation is running on a secure network, the administrator can disable this warning message by setting SUPPRESS_TWOFACTOR_WARNING to true in config/site.php</p>
		</div>
	    
	   </div>
	 </div>
	 
	 <?php } ?>
	 
	 
	
	

    <?php if( !$otp ) { //no need for password in OTP mode ?>
    <a name="forgot_password"></a>

    <form method="post" action="<?php echo $this->url('/login', 'forgot_password')?>" class="form-horizontal">
        <div class="row">
            <div class="span10 offset1">

                <h3><?php echo t('Forgot Your Password?')?></h3>

                <p><?php echo t("Enter your email address below. We will send you instructions to reset your password.")?></p>

                <input type="hidden" name="rcID" value="<?php echo $rcID?>" />

                <div class="control-group">
                    <label for="uEmail" class="control-label"><?php echo t('Email Address')?></label>
                    <div class="controls">
                        <input type="text" name="uEmail" value="" class="ccm-input-text" >
                    </div>
                </div>

                <div class="actions">
                    <?php echo $form->submit('submit', t('Reset and Email Password') . ' &gt;')?>
                </div>

            </div>
        </div>
    </form>
    <?php } ?>

    <?php  if (ENABLE_REGISTRATION == 1) { ?>
        <div class="row">
            <div class="span10 offset1">
                <div class="control-group">
                    <h3><?php echo t('Not a Member')?></h3>
                    <p><?php echo t('Create a user account for use on this website.')?></p>
                    <div class="actions">
                        <a class="btn" href="<?php echo $this->url('/register')?>"><?php echo t('Register here!')?></a>
                    </div>
                </div>
            </div>
        </div>
    <?php  } ?>

<?php  } ?>
<div class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Modal header</h3>
    </div>
    <div class="modal-body">
        <p>One fine bodyâ€¦</p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn">Close</a>
        <a href="#" class="btn btn-primary">Save changes</a>
    </div>
</div>