<?php defined('C5_EXECUTE') or die("Access Denied."); 
/**
 * Customized login page
 * (c) 2014 PasswordX
 * Apache v2 License
 */
  Loader::library('authentication/open_id');
  $form = Loader::helper('form');
  $nsa = Loader::helper('nsa'); 
?>

<script type="text/javascript">
    $(function() {
        $("input[name=uName]").focus();

        //logic for requesting SMS tokens
       $("#request_sms").click(function(){

           phone = prompt("<?php echo t('Please enter your phone number') ?>");

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
    <div class="alert alert-success"><p><?php echo $intro_msg?></p></div>
<?php  } ?>

<div class="row">
    <div class="col-lg-12">
   	<?php 
	 $stack = Stack::getByName('Login Page Content');
     $stack->display();
	?>  
    </div>
</div>


	<?php
	 //Check for SSL Encrypted
	 if (($nsa->connectionUnsecured()) && (!SUPPRESS_SSL_WARNING)) { //Connection is insecure
	 ?>

  <div class="panel panel-danger">
    <div class="panel-heading">
      <div class="panel-title">
		<strong><i class="glyphicon glyphicon-warning-sign"></i> <?php echo t('This connection is not secure. Please set up SSL encryption.')?></strong>
		<a class="pull-right" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><i class="glyphicon glyphicon-chevron-down"></i> </a>
	 </div>
    </div>
    <div id="collapseOne" class="panel-collapse collapse">
      <div class="panel-body">		

	  
	    <p>This connection is not secure at the moment as you are connecting through plain HTTP. This means you are susceptible to <a href="http://en.wikipedia.org/wiki/Man-in-the-middle_attack" target="_blank">man-in-the-middle attacks</a>. </p>
		
		<p>How you set up SSL depends on what your webserver setup is. You do not need to buy an SSL certificate, as an unsigned certificate you issue yourself is just as secure as an unsigned one. However, users will see a browser warning if you serve an unsigned certificate.</p>
	
		<p>If you need easy and hassle-free HTTPS we recommend using <a href="https://www.cloudflare.com/" target="_blank">Cloudflare</a> as your DNS provider, as they have a very affordable turnkey SSL encryption (along with a host of other benefits such as CDN, DDOS attack mitigation and more) in their paid Pro plans.</p>
		
		<div class="alert alert-info">
		<p><i class="glyphicon glyphicon-info-sign"></i> If this installation is running on a secure network, the administrator can disable this warning message by setting SUPPRESS_SSL_WARNING to true in config/site.php</p>
		</div>
		
      </div>
    </div>
  </div>		
		
	 <?php } ?>	
	
	<?php
	 //Check for Two-Factor enabled
	 if ( ($nsa->disabledTwoFactor()) && (!SUPPRESS_TWOFACTOR_WARNING)) { //Two-factor is disabled atm
	 ?>
	 
  <div class="panel panel-danger">
    <div class="panel-heading">
      <div class="panel-title">
		<strong><i class="glyphicon glyphicon-warning-sign"></i> <?php echo t('Two-Factor Authentication is disabled!')?></strong>
		<a class="pull-right" data-toggle="collapse"  href="#collapseTwo"><i class="glyphicon glyphicon-chevron-down"></i> </a>
	 </div>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body">		 
	  
	   <p>If this system is publicly accessible on the web, it is strongly recommended that you enable two-factor authentication. If you do not know what two-factor authentication is, <a href="http://en.wikipedia.org/wiki/Two-step_verification" target="_blank">read more at Wikipedia.</a> You should only turn off two-factor authentication if your installation is behind a firewall and only accessible on a LAN or through a VPN.</p>
	   
	   <p>You can configure Two-Factor authentication in the Dashboard after logging in</p>
	   
	   <div class="alert alert-info">
	   	<p><i class="glyphicon glyphicon-info-sign"></i> If this installation is running on a secure network, the administrator can disable this warning message by setting SUPPRESS_TWOFACTOR_WARNING to true in config/site.php</p>
		</div>
		
      </div>
    </div>
  </div>			
	    
	 <?php } ?>

 <div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
	
<div class="panel panel-primary">
<div class="panel-heading">Please sign in</div>
  <div class="panel-body">
	
    <form role="form" method="post" action="<?php echo $this->url('/login', 'do_login')?>" >
        <?php  $rcID = isset($_REQUEST['rcID']) ? Loader::helper('text')->entities($_REQUEST['rcID']) : $rcID; ?>
        <input type="hidden" name="rcID" value="<?php echo $rcID?>" />	

							<?php  if (isset($locales) && is_array($locales) && count($locales) > 0) { ?>
                                <div class="form-group">
                                    <label for="USER_LOCALE"><?php echo t('Language')?></label>
                                    <?php echo $form->select('USER_LOCALE', $locales)?>
                                </div>
                            <?php  } ?>
							
                            <div class="form-group">
                                <label for="uName" ><?php  if (USER_REGISTRATION_WITH_EMAIL_ADDRESS == true) { ?>
                                        <?php echo t('Email Address')?>
                                    <?php  } else { ?>
                                        <?php echo t('Username')?>
                                    <?php  } ?></label>
                                    <input tabindex="1" type="text" name="uName" id="uName" <?php echo  (isset($uName)?'value="'.$uName.'"':'');?> class="form-control" >
                            </div>
							
                            <?php if( !$otp ) { ?>
                                <div class="form-group">
                                    <label for="uPassword"><?php echo t('Password')?></label>
                                    <input tabindex="2" type="password" name="uPassword" id="uPassword" class="form-control" />
                                </div>
                            <?php } ?>
							
                            <?php if($authy_enabled){ ?>
                                <div class="form-group">
                                    <label for="uPassword">
                                           <?php echo t('Token')?>
                                            <?php if( $sms ) { ?>
                                                <a tabindex="4" href="javascript:void(0);" id="request_sms" style="font-size: 10px; display:block; margin-top: -5px; outline: none;">(<?php echo t('Request SMS token') ?>)</a>
                                            <?php } ?>
                                    </label>

                                    <input tabindex="3" type="text" name="uToken" id="uToken" maxlength="7" class="form-control" />
                                </div>
                            <?php } ?>
							
                            <div class="checkbox">
							<label >
							 <?php echo $form->checkbox('uMaintainLogin', 1)?> <span><?php echo t('Stay signed in for 2 weeks.')?> <strong>Warning:</strong> Do NOT check this box if you are logging in from a public location</span>
							</label>
                            </div>							
					

                            <?php echo $form->submit('submit', t('Sign In') . ' &gt;', array('class' => 'btn-primary'))?>
				
    </form>
	
	</div>
	</div>

            </div>
			<div class="col-lg-3"></div>
        </div>

    <?php if( !$otp ) { //no need for password in OTP mode ?>
	<div class="row">
		<div class="col-lg-3"></div>
		<div class="col-lg-6">
	
	<div class="panel panel-default">
	<div class="panel-heading">
	 <div class="panel-title">
	<a data-toggle="collapse"  href="#collapseForgot"><?php echo t('Forgot Your Password?')?></a>
	  </div>
	</div> 
	<div id="collapseForgot" class="panel-collapse collapse">
	<div class="panel-body">
    <a name="forgot_password"></a>
	<p>The only way to recover access to this system is to ask the administrator to reset your password for you.</p>
	<p>If you are the administrator and have lost access to the system, you can recover access using your backup key. Please <a href="<?php echo RECOVER_ACCESS_URL; ?>" target="_blank">see these instructions for more information.</a></p>
 	
	</div>
	</div>
	</div>

            </div>
			<div class="col-lg-3"></div>
        </div>
    <?php } ?>		
	