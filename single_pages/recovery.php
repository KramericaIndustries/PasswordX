<?php
/**
 * Recovery view
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied."); 
$nsa = Loader::helper('nsa');
?>
<div class="container" style="margin-top: 100px;">

	<div class="panel panel-primary">
    <div class="panel-heading">
      <div class="panel-title">
		<strong><i class="glyphicon glyphicon-info-sign"></i> <?php echo t('Recovery your passord!')?></strong>
	 </div>
    </div>
      <div class="panel-body">		 
	  
	   <p>This page allows only for the administrator to reset it password.</p>
	   
	   <p>For more details, please visit <a href="#">the wiki section</a>.</p>
	   
	   <p>Upload the recovey key you downloaded at install time under WEB_ROOT/config/recovery/recovery_key.rsa.</p>
	   
	   <p>Once the process is completed, you will be redirected to your profile page, where you will have to set up a new password.</p>
	   
	   <?php if(!$recoveryTest) { ?>
		   <div class="alert alert-danger">
			  <p><strong>Oh snap!</strong> Could not find the recovery key for reseting your password. Please be sure that you uploaded it under WEB_ROOT/config/recovery/recovery_key.rsa.</p>
			  <p><a href="/recovery" class="btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Re-run the test</a></p>
		   </div>
	   <?php } ?>
	   
	   <?php if(!$masterTest) { ?>
		   <div class="alert alert-danger">
			  <p><strong>Opppsss! This is baaaad!</strong> the master encryption file could not be found. This should have not happen. Expected to find file in WEB_ROOT/config/recovery/master_key.</p>
			  <p><a href="/recovery" class="btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Re-run the test</a></p>
		   </div>
	   <?php } ?>
	   
	   <?php if( $recoveryTest && $masterTest ) { ?>
	   		<div class="alert alert-info">
			  <p><strong>Ready, sir?</strong> Found the recovery key. You can start the process the pressing botton below!</p>
			  <form method="post" action="/recovery/reset_passwd">
			  <?php
			  	//security token
			  	$token = Loader::helper('validation/token');
				echo $token->output('password_reset');
			  ?>	
			  <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
			  	<span class="glyphicon glyphicon-pencil"></span> Recover your password!
			  </button>
			  </form>
		   </div> 
	   <?php } ?>
      </div>
  </div>			

</div>