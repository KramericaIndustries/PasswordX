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
		<strong><i class="glyphicon glyphicon-info-sign"></i> <?php echo t('Recover access to PasswordX')?></strong>
	 </div>
    </div>
      <div class="panel-body">		 
	  
	   <p>This page allows the administrator to reset their password. For more information, please visit <a href="<?php echo RECOVER_ACCESS_URL; ?>">our wiki</a>.</p>
	   
	   <strong>Steps to recover access to this system:</strong>
	   
	   <ul style="list-style-type: disc;">
	    <li>Upload the recovey key you downloaded at install time to /config/recovery/recovery_key.rsa.</li>
		<li>Refresh this page and click the Recover button</li>
		<li>Once the process is completed, you will be redirected to your profile page, where you will have to set up a new password.</li>
	  </ul>

	  	<?php if(!$masterTest) { ?>
		   <div class="alert alert-danger">
			  <p><strong>FATAL ERROR</strong></p>
			  <p>The encrypted master key file could not be found. This means that a recovery of your system cannot be completed at this time. The encrypted master key file should be located at /config/recovery/master_key.</p>
		   </div>
			<a href="/recovery" class="btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Re-run the test</a>
	   
	   <?php } else if(!$recoveryTest) { ?>
		   <div class="alert alert-danger">
			  <p><strong>Oh snap! Could not find the recovery key. </strong></p>
			  <p>Please make sure that you have uploaded the key file to /config/recovery/recovery_key.rsa.</p>
		   </div>
		   <a href="/recovery" class="btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Re-run the test</a>
	   <?php } ?>
	   
	   <?php if( $recoveryTest && $masterTest ) { ?>
	   		<div class="alert alert-success">
			  <p><strong>Recovery key found</strong></p>
			  <p>You can start the recovery process the pressing button below.</p>
		   </div> 

			  <form method="post" action="/recovery/reset_passwd">
			  <?php
			  	//security token
			  	$token = Loader::helper('validation/token');
				echo $token->output('password_reset');
			  ?>	
			  <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
			  	<span class="glyphicon glyphicon-ok"></span> Recover access to PasswordX
			  </button>
			  </form>		   
	   <?php } ?>
      </div>
  </div>			

</div>