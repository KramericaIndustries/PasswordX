<?php 
/**
 * Install view 
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied."); ?> 

<script type="text/javascript" src="<?php echo ASSETS_URL_JAVASCRIPT?>/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL_JAVASCRIPT?>/jquery.cookie.js"></script>
<script type="text/javascript">
$(function() {
	$(".launch-tooltip").tooltip({
		placement: 'bottom'
	});
});
</script>
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
<?php 

$introMsg = t('To install PasswordX, please fill out the form below.');

if (isset($successMessage)) { ?>

<script type="text/javascript">
$(function() {

$("#recovery-key-link").click(function(){
	$("#continue_to_system").removeAttr('disabled');
});
	
<?php for ($i = 1; $i <= count($installRoutines); $i++) {
	$routine = $installRoutines[$i-1]; ?>

	ccm_installRoutine<?php echo $i?> = function() {
		<?php if ($routine->getText() != '') { ?>
			$("#install-progress-summary").html('<?php echo addslashes($routine->getText())?>');
		<?php } ?>
		$.ajax('<?php echo $this->url("/install", "run_routine", $installPackage, $routine->getMethod())?>', {
			dataType: 'json',
			error: function(r) {
				$("#install-progress-wrapper").hide();
				$("#install-progress-errors").append('<div class="alert-message error">' + r.responseText + '</div>');
				$("#install-progress-error-wrapper").fadeIn(300);
			},
			success: function(r) {
				if (r.error) {
					$("#install-progress-wrapper").hide();
					$("#install-progress-errors").append('<div class="alert-message error">' + r.message + '</div>');
					$("#install-progress-error-wrapper").fadeIn(300);
				} else {
					$('#install-progress-bar div.bar').css('width', '<?php echo $routine->getProgress()?>%');
					<?php if ($i < count($installRoutines)) { ?>
						ccm_installRoutine<?php echo $i+1?>();
					<?php } else { ?>
						$("#install-progress-wrapper").fadeOut(300, function() {
							$("#success-message").fadeIn(300);
						});
					<?php } ?>
				}
			}
		});
	}
	
<?php } ?>

	ccm_installRoutine1();

});

</script>

<div class="row">
<div class="span10 offset1">
<div class="page-header">
<h1><?php echo t('Install PasswordX')?></h1>
<p><?php echo t('Version %s', APP_VERSION)?></p>
</div>
</div>
</div>


<div class="row">
<div class="span10 offset1">

<div id="success-message">
<?php echo $successMessage?>
<br/><br/>
<div class="alert alert-warning">
<h4><i class="icon-warning-sign"></i> Don't get locked out of the system!</h4>
<p>If you forget your password, you will need to use the recovery key in order to reset it and gain access to the system again.</p>
<p>Keep the recovery key in a secured place! After this step you will not be able to obtain this key again!</p>
<p>If you lose this key you <strong>will not</strong> be able to access the system or recover any of the data!</p>
<a class="btn btn-primary" id="recovery-key-link" href="/config/recovery/recovery_key.rsa" target="_blank" download>
	<i class="icon-download icon-white" style="margin-top: 1px"></i> Download the recovery key
</a>
</div>
<br/><br/>
<div class="well">
<a href="<?php echo DIR_REL?>/" class="btn large primary" id="continue_to_system" disabled>
	<?php echo t('Continue to the password system')?> <i class="icon-arrow-right icon-white" style="margin-top: 1px;"></i>
</a>
</div>
</div>

<div id="install-progress-wrapper">
<div class="alert-message info">
<div id="install-progress-summary">
<?php echo t('Beginning Installation')?>
</div>
</div>

<div id="install-progress-bar">
<div class="progress progress-striped active">
<div class="bar" style="width: 0%;"></div>
</div>
</div>

</div>

<div id="install-progress-error-wrapper">
<div id="install-progress-errors"></div>
<div id="install-progress-back">
<input type="button" class="btn" onclick="window.location.href='<?php echo $this->url('/install')?>'" value="<?php echo t('Back')?>" />
</div>
</div>
</div>
</div>

<?php } else if ($this->controller->getTask() == 'setup' || $this->controller->getTask() == 'configure') { ?>

<link href="//cdnjs.cloudflare.com/ajax/libs/authy-forms.css/2.0/form.authy.min.css" media="screen" rel="stylesheet" type="text/css">
<script src="/js/form.authy.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
	$("#sample-content-selector td").click(function() {
		$(this).parent().find('input[type=radio]').prop('checked', true);
		$(this).parent().parent().find('tr').removeClass();
		$(this).parent().addClass('package-selected');
	});
});
</script>

<div class="row">
<div class="span10 offset1">

<div class="page-header">
<h1><?php echo t('Install PasswordX')?></h1>
<p><?php echo t('Version %s', APP_VERSION)?></p>
</div>

</div>
</div>


<form action="<?php echo $this->url('/install', 'configure')?>" method="post" class="form-horizontal">

<div class="row">
<div class="span5 offset1">

	<input type="hidden" name="locale" value="<?php echo $locale?>" />
	
	<!--
	<fieldset>
		<legend style="margin-bottom: 0px"><?php echo t('Site Information')?></legend>
		<div class="control-group">
		<label for="SITE" class="control-label"><?php echo t('Name Your Site')?>:</label>
		<div class="controls">
			<?php echo $form->text('SITE', array('class' => 'xlarge', 'value'=>'PasswordX'))?>
		</div>
		</div>
			
	</fieldset>
	-->
	
	<input id="SITE" type="hidden" name="SITE" value="PasswordX" class="xlarge ccm-input-text">
	
	<fieldset>
		<legend style="margin-bottom: 0px"><?php echo t('Administrator Information')?></legend>
		<div class="clearfix">
		<label for="uName"><?php echo t('Real Name')?>:</label>
		<div class="input">
			<?php echo $form->text('uName', array('class' => 'xlarge', 'placeholder'=>'Your username will be admin'))?>
		</div>
		</div>
		<div class="clearfix">
		<label for="uEmail"><?php echo t('Email Address')?>:</label>
		<div class="input">
			<?php echo $form->email('uEmail', array('class' => 'xlarge', 'placeholder'=>'youremail@yourprovider.com'))?>
		</div>
		</div>
		
		<div class="clearfix">
		<label for="uPassword"><?php echo t('Password')?>:</label>
		<div class="input">
			<?php echo $form->password('uPassword', array('class' => 'xlarge', 'placeholder'=>'At least 5 characters long'))?>
		</div>
		</div>
		
		<div class="clearfix">
		<label for="uPasswordConfirm"><?php echo t('Confirm Password')?>:</label>
		<div class="input" >
			<?php echo $form->password('uPasswordConfirm', array('class' => 'xlarge', 'placeholder'=>'You don\'t want to miss typos'))?>
		</div>
		</div>
		
		<div class="clearfix">
		 <label for=""><?php echo t('Password strength')?>:</label>
		 <div class="input" id="password_input">
		  <span id="pwstrength_viewport_progress"></span> <span id="pwstrength_viewport_verdict"></span>
		 </div>
		</div>		
		
	</fieldset>
	
	<!-- Password strength meter for Bootstrap 
		https://github.com/ablanco/jquery.pwstrength.bootstrap
		https://tech.dropbox.com/2012/04/zxcvbn-realistic-password-strength-estimation/
	-->
<script type="text/javascript" src="/js/zxcvbn-async.js"></script>
<script type="text/javascript" src="/js/pwstrength.js"></script>
<script type="text/javascript">
        jQuery(document).ready(function () {
 
            var options = {};
            options.ui = {
				bootstrap2: true,
                container: "#password_input",
                viewports: {
                    progress: "#pwstrength_viewport_progress",
                    verdict: "#pwstrength_viewport_verdict"
                }
            };
            options.common = {
				minChar: 5,
                onLoad: function () {
				 $('.password-verdict').html("Try to get close to a full green bar.");
                },
                zxcvbn: true
            };
            $('#uPassword').pwstrength(options);
        });
    </script>	
	

</div>
<div class="span5">

	<fieldset>
		<legend style="margin-bottom: 0px"><?php echo t('Database Information')?></legend>

	<div class="clearfix">
	<label for="DB_SERVER"><?php echo t('Server')?>:</label>
	<div class="input">
		<?php echo $form->text('DB_SERVER', array('class' => 'xlarge', 'placeholder'=>'e.g. localhost'))?>
	</div>
	</div>

	<div class="clearfix">
	<label for="DB_USERNAME"><?php echo t('MySQL Username')?>:</label>
	<div class="input">
		<?php echo $form->text('DB_USERNAME', array('class' => 'xlarge'))?>
	</div>
	</div>

	<div class="clearfix">
	<label for="DB_PASSWORD"><?php echo t('MySQL Password')?>:</label>
	<div class="input">
		<?php echo $form->password('DB_PASSWORD', array('class' => 'xlarge'))?>
	</div>
	</div>

	<div class="clearfix">
	<label for="DB_DATABASE"><?php echo t('Database Name')?>:</label>
	<div class="input">
		<?php echo $form->text('DB_DATABASE', array('class' => 'xlarge'))?>
	</div>
	</div>

		<p class="clearfix">
		 <strong>Note:</strong> It is important that the administrator password is as secure as you can make it. It is OK to choose one of your &quot;usual&quot; passwords, as long as you enable two-factor authentication. See this helpful illustration om <a href="https://xkcd.com/936/" target="_blank">how to choose passwords</a>
		</p>		
	
	
	</fieldset>

</div>
</div>

<div class="row">
<div class="span10 offset1">

	<fieldset>

	 <legend ><?php echo t('Configure Two-Factor Login')?> 
	 <!-- Woow, check out this oldschool layout magic! I feel like it's 1999 all over again -->
	 <!-- &nbsp;&nbsp;&nbsp;&nbsp; AWESOME!! &nbsp;&nbsp;&nbsp;&nbsp; -->
	  <br><sup>In addition to your password, require that you type in a code you receive on your phone - optional but recommended</sup>
	 </legend>

	
	<p style="text-align:justify;">If this system is publicly accessible on the web, it is strongly recommended that you enable two-factor authentication. If you do not know what two-factor authentication is, <a href="http://en.wikipedia.org/wiki/Two-step_verification" target="_blank">read more at Wikipedia.</a> You should only skip this step / turn off two-factor authentication if your installation is behind a firewall and only accessible on a LAN or through a VPN. PasswordX provides Authy integration for two-factor which requires you to <a href="https://dashboard.authy.com/signup" target="_blank">create an Authy developer account</a> and create an API key. It is <a href="https://www.authy.com/developer/pricing" target="_blank">free to get started with Authy</a>, and they offer a range of awesome customization options as well as SMS token support.</p>

	<div class="clearfix" style="margin-top: 25px;">
	<label for="AUTHY_API_KEY"><?php echo t('Two-Factor Authentication Method')?>:</label>
	<div class="input">
		<?php 
		$two_factor_options = array(
			"authy"=>"Use Authy integration",
			"google"=>"Use Google Authenticator",
			"no_2factor"=>"Turn off two-factor for now, I'll set this up later"
		); 
		echo $form->select('TWO_FACTOR_AUTH_METHOD', $two_factor_options, "authy", array('class' => 'xlarge'))?>
	</div>
	</div>
	
	<!-- Config specific to Authy-->
	<div id="authy_config_options" class="2fa_config_options" style="display: none;">
	
		<div class="clearfix">
		<label for="AUTHY_API_KEY"><?php echo t('Authy API Key')?>:</label>
		<div class="input">
			<?php echo $form->text('AUTHY_API_KEY', array('class' => 'xlarge'))?>
		</div>
		</div>
		
		<div class="clearfix">
		 <label for="phoneNumber"><?php echo t('Phone number')?>:</label>
		  <div class="input">
				<?php echo $form->text('authy-cellphone', array('class' => 'xlarge' ))?>
		  </div>
		</div>
		
		<div class="clearfix">
		 <label for="countryCode"><?php echo t('Country Code')?>:</label>
		  <div class="input">
		  	<?php echo $form->text('countryCode'); ?>
			<div style="margin-top: 10px;"><strong>Note:</strong> When requesting a token SMS, this country code will be used as default</div>
		  </div>
		</div>
	
	</div>
	
	<div id="google_config_options" class="2fa_config_options" style="display: none;">
		TODO; COMMING UP SOON;
	</div>

	</fieldset>
	
<script type="text/javascript">
 $(function() {
 
 //on page load, show the propper 2fa config form
 $("#" + $('#TWO_FACTOR_AUTH_METHOD').val() + "_config_options").show();
 $("#" + $('#TWO_FACTOR_AUTH_METHOD').val() + "_config_options").addClass("active");
 
  $('#TWO_FACTOR_AUTH_METHOD').change(function() {
  
    if( $(".2fa_config_options.active").length > 0 ) {
    	
	    $(".2fa_config_options.active").fadeOut(function(){
		   	 //and shopw the propper one
			 $(".2fa_config_options").removeClass("active");
		 	 $("#" + $('#TWO_FACTOR_AUTH_METHOD').val() + "_config_options").addClass("active");
			 $("#" + $('#TWO_FACTOR_AUTH_METHOD').val() + "_config_options").fadeIn();	
	   	 });
	   	 	
    } else {
    	//skip fade out
    	$("#" + $('#TWO_FACTOR_AUTH_METHOD').val() + "_config_options").addClass("active");
		$("#" + $('#TWO_FACTOR_AUTH_METHOD').val() + "_config_options").fadeIn();
    }
 
  });

});

//Load Authy CC selector
window.onload = function() {
    return Authy.UI.instance( "countryCode" );
};

</script>	

</div>
</div>


<div class="row">
<div class="span10 offset1">

	<fieldset>
	<legend ><?php echo t('Configure SSL')?>
		<!-- I built a time machine in 1999 in order to come here and write this: -->
	 <br><sup>SSL ensures that all passwords are transmitted across the wire in an encrypted state - optional but recommended</sup>
	 
	</legend>
	<p style="text-align:justify;">If this system is publicly accessible on the web, it is strongly recommended that you enable SSL encryption for this host on your webserver. If you do not set up HTTPS for PasswordX, we will warn you on the login screen that you are accessing your secret data through an unencrypted connection and that you are vulnerable to <a href="http://en.wikipedia.org/wiki/Man-in-the-middle_attack" target="_blank">man-in-the-middle attacks</a>. </p>
	
	<p style="text-align:justify;">How you set up SSL depends on what your webserver setup is. You do not need to buy an SSL certificate, as an unsigned certificate you issue yourself is just as secure as an unsigned one. However, users will see a browser warning if you serve an unsigned certificate.</p>
	
	<p style="text-align:justify;">If you need easy and hassle-free HTTPS we recommend using <a href="https://www.cloudflare.com/" target="_blank">Cloudflare</a> as your DNS provider, as they have a very affordable turnkey SSL encryption (along with a host of other benefits such as CDN, DDOS attack mitigation and more) in their paid Pro plans.</p>
	

	</fieldset>
</div>
</div>


<?php

//To be added automatically to site.php
$configs = array(
	"ENABLE_MARKETPLACE_SUPPORT"	=>	"false",
	"ENABLE_NEWSFLOW_OVERLAY"		=>	"false",
	"ENABLE_APP_NEWS"				=>	"false",
	"ENABLE_APPLICATION_EVENTS"		=>	"true",
	"PERMISSIONS_MODEL"				=>	"advanced",
	"DATE_FORM_HELPER_FORMAT_HOUR" 	=> "24",
	"DATE_APP_DATE_PICKER"			=>	"dd-mm-yy",
	"DATE_APP_GENERIC_MDYT_FULL" 	=> "j. F Y - G:i",
	"DATE_APP_GENERIC_MDYT"			=>	"j-n-Y - G:i",
	"DATE_APP_GENERIC_MDY"			=>	"j-n-Y",
	"DATE_APP_GENERIC_MDY_FULL"		=> "j. F Y",	
	"DATE_APP_DATE_ATTRIBUTE_TYPE_T"=>	"G:i:s",
	"DATE_APP_GENERIC_T"			=>	"G:i:s",
	"DATE_APP_GENERIC_TS"			=>	"G:i:s",
	"UP_SINCE"						=> date('Y-m-d H:i:s'),
	"ENABLE_INTELLIGENT_SEARCH_HELP"=>"false",
	"ENABLE_INTELLIGENT_SEARCH_MARKETPLACE"=>"false",
	"WHITE_LABEL_APP_NAME"			=>	"PasswordX",
	"WHITE_LABEL_LOGO_SRC"			=>	"/images/logo_menu.png",
	"CONCRETE5_ORG_URL"				=>	"http://www.concrete5.org/r/-/167051",
	"RECOVER_ACCESS_URL"			=>	"http://www.google.com",
	"GITHUB_REPO_URL"				=>	"http://www.github.com",
	"GITHUB_ISSUES_URL"				=>	"http://www.github.com",
	"SUPPRESS_SSL_WARNING"			=>	"false",
	"SUPPRESS_TWOFACTOR_WARNING"	=>	"false",
);
foreach( $configs as $k=>$v ) { ?>
	<input type="hidden" name="SITE_CONFIG[<?php echo $k ?>]" value="<?php echo $v ?>" />	
<?php } ?>

<!--
	THEME CHOISE HERE WILL BE LATER -->
<div class="row" style="margin-top:25px;">
<div class="span10 offset1">

<h3><?php echo t('Install Mode')?></h3>

		
		<?php
		$uh = Loader::helper('concrete/urls');
		?>
		
		<table class="table table-striped" id="sample-content-selector">
		<tbody>
		<?php 
		$availableSampleContent = StartingPointPackage::getAvailableList();
		foreach($availableSampleContent as $spl) { 
			$pkgHandle = $spl->getPackageHandle();
		?>

		<tr class="<?php if ($this->post('SAMPLE_CONTENT') == $pkgHandle || (!$this->post('SAMPLE_CONTENT') && $pkgHandle == 'standard') || count($availableSampleContent) == 1) { ?>package-selected<?php } ?>">
			<td><?php echo $form->radio('SAMPLE_CONTENT', $pkgHandle, ($pkgHandle == 'standard' || count($availableSampleContent) == 1))?></td>
			<td class="sample-content-thumbnail"><img src="<?php echo $uh->getPackageIconURL($spl)?>" width="97" height="97" alt="<?php echo $spl->getPackageName()?>" /></td>
			<td class="sample-content-description" width="100%"><h4><?php echo $spl->getPackageName()?></h4><p><?php echo $spl->getPackageDescription()?></td>
		</tr>
		
		<?php } ?>
		
		</tbody>
		</table>

</div>
</div>


<div class="row">
<div class="span10 offset1">

<div class="well">
	<button class="btn btn-large primary" type="submit"><?php echo t('Install PasswordX')?> <i class="icon-thumbs-up icon-white"></i></button>
</div>

</div>
</div>

</form>


<?php } else if (isset($locale) || count($locales) == 0) { ?>

<script type="text/javascript">

$(function() {
	$("#install-errors").hide();
});

<?php if ($this->controller->passedRequiredItems()) { ?>
	var showFormOnTestCompletion = true;
<?php } else { ?>
	var showFormOnTestCompletion = false;
<?php } ?>


$(function() {
	$(".ccm-test-js img").hide();
	$("#ccm-test-js-success").show();
	if ($.cookie('CONCRETE5_INSTALL_TEST')) {
		$("#ccm-test-cookies-enabled-loading").attr('src', '<?php echo ASSETS_URL_IMAGES?>/icons/success.png');
	} else {
		$("#ccm-test-cookies-enabled-loading").attr('src', '<?php echo ASSETS_URL_IMAGES?>/icons/error.png');
		$("#ccm-test-cookies-enabled-tooltip").show();
		$("#install-errors").show();
		showFormOnTestCompletion = false;
	}
	$("#ccm-test-request-loading").ajaxError(function(event, request, settings) {
		$(this).attr('src', '<?php echo ASSETS_URL_IMAGES?>/icons/error.png');
		$("#ccm-test-request-tooltip").show();
		showFormOnTestCompletion = false;
	});
	$.getJSON('<?php echo $this->url("/install", "test_url", "20", "20")?>', function(json) {
		// test url takes two numbers and adds them together. Basically we just need to make sure that
		// our url() syntax works - we do this by sending a test url call to the server when we're certain 
		// of what the output will be
		if (json.response == 40) {
			$("#ccm-test-request-loading").attr('src', '<?php echo ASSETS_URL_IMAGES?>/icons/success.png');
			if (showFormOnTestCompletion) {
				$("#install-success").show();
			} else {
				$("#install-errors").show();
			}
		} else {
			$("#ccm-test-request-loading").attr('src', '<?php echo ASSETS_URL_IMAGES?>/icons/error.png');
			$("#ccm-test-request-tooltip").show();
			$("#install-errors").show();
		}
	});
	
});
</script>

<div class="row">

<div class="span10 offset1">
<div class="page-header">
	<h1><?php echo t('Install PasswordX')?></h1>
	<p><?php echo t('Version %s', APP_VERSION)?></p>
</div>

<h3><?php echo t('Testing Required Items')?></h3>
</div>
</div>

<div class="row">
<div class="span5 offset1">

<table class="table table-striped">
<tbody>
<tr>
	<td class="ccm-test-phpversion"><?php if ($phpVtest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/success.png" /><?php } else { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/error.png" /><?php } ?></td>
	<td width="100%"><?php echo t(/*i18n: %s is the php version*/'PHP %s', $phpVmin)?></td>
	<td><?php if (!$phpVtest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo t('concrete5 requires at least PHP %s', $phpVmin)?>" /><?php } ?></td>
</tr>
<tr>
	<td class="ccm-test-js"><img id="ccm-test-js-success" src="<?php echo ASSETS_URL_IMAGES?>/icons/success.png" style="display: none" />
	<img src="<?php echo ASSETS_URL_IMAGES?>/icons/error.png" /></td>
	<td width="100%"><?php echo t('JavaScript Enabled')?></td>
	<td class="ccm-test-js"><img src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo t('Please enable JavaScript in your browser.')?>" /></td>
</tr>
<tr>
	<td><?php if ($mysqlTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/success.png" /><?php } else { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/error.png" /><?php } ?></td>
	<td width="100%"><?php echo t('MySQL Available')?>
	</td>
	<td><?php if (!$mysqlTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo $this->controller->getDBErrorMsg()?>" /><?php } ?></td>
</tr>
<tr>
	<td><img id="ccm-test-request-loading"  src="<?php echo ASSETS_URL_IMAGES?>/dashboard/sitemap/loading.gif" /></td>
	<td width="100%"><?php echo t('Supports for pretty URLs')?>
	</td>
	<td><img id="ccm-test-request-tooltip" src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo t('concrete5 cannot parse the PATH_INFO or ORIG_PATH_INFO information provided by your server.')?>" /></td>
</tr>
<tr>
	<td><?php if ($curlTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/success.png" /><?php } else { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/error.png" /><?php } ?></td>
	<td width="100%"><?php echo t('Support for cURL')?>
	</td>
	<td><?php if (!$curlTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo t('cURL library is required in order to provide 2 factor authentication through Authy.')?>" /><?php } ?></td>
</tr>
<tr>
	<td><?php if ($hashTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/success.png" /><?php } else { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/error.png" /><?php } ?></td>
	<td width="100%"><?php echo t('Support for hash functions')?>
	</td>
	<td><?php if (!$hashTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo t('hash functions are required.')?>" /><?php } ?></td>
</tr>
</table>
</div>
<div class="span5">

<table class="table table-striped">

<tr>
	<td><?php if ($imageTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/success.png" /><?php } else { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/error.png" /><?php } ?></td>
	<td width="100%"><?php echo t('Image Manipulation Available')?>
	</td>
	<td><?php if (!$imageTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo t('concrete5 requires GD library 2.0.1 or greater')?>" /><?php } ?></td>
</tr>
<tr>
	<td><?php if ($xmlTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/success.png" /><?php } else { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/error.png" /><?php } ?></td>
	<td width="100%"><?php echo t('XML Support')?>
	</td>
	<td><?php if (!$xmlTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo t('concrete5 requires PHP XML Parser and SimpleXML extensions')?>" /><?php } ?></td>
</tr>
<tr>
	<td><?php if ($fileWriteTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/success.png" /><?php } else { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/error.png" /><?php } ?></td>
	<td width="100%"><?php echo t('Writable Files and Configuration Directories')?>
	</td>
	<td><?php if (!$fileWriteTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo t('The config/, packages/ and files/ directories must be writable by your web server.')?>" /><?php } ?></td>
</tr>
<tr>
	<td><img id="ccm-test-cookies-enabled-loading"  src="<?php echo ASSETS_URL_IMAGES?>/dashboard/sitemap/loading.gif" /></td>
	<td width="100%"><?php echo t('Cookies Enabled')?>
	</td>
	<td><img id="ccm-test-cookies-enabled-tooltip" src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo t('Cookies must be enabled in your browser to install concrete5.')?>" /></td>
</tr>
<tr>
	<td><?php if ($mcryptTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/success.png" /><?php } else { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/error.png" /><?php } ?></td>
	<td width="100%"><?php echo t('Support for mcrypt')?>
	</td>
	<td><?php if (!$mcryptTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo t('mcrypt library is required in order to safely store password in the database.')?>" /><?php } ?></td>
</tr>
</tbody>
</table>

</div>
</div>


<div class="row">
<div class="span10 offset1">

<h3><?php echo t('Testing Optional Items')?></h3>

</div>
</div>

<div class="row">
<div class="span5 offset1">

<!-- First Column Optional -->
<table class="table table-striped">
<tbody>
<tr>
	<td><?php if ($remoteFileUploadTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/success.png" /><?php } else { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/warning.png" /><?php } ?></td>
	<td width="100%"><?php echo t('Remote File Importing Available')?>
	</td>
	<td><?php if (!$remoteFileUploadTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo t('Remote file importing through the file manager requires the iconv PHP extension.')?>" /><?php } ?></td>
</tr>
</tbody>
</table>

</div>
<div class="span5">

	<!-- Second Column Optional -->
	<table class="table table-striped">
	<tbody>
	<tr>
		<td><?php if ($opensslTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/success.png" /><?php } else { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/error.png" /><?php } ?></td>
		<td width="100%"><?php echo t('Support for OpenSSL')?>
		</td>
		<td><?php if (!$opensslTest) { ?><img src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" class="launch-tooltip" title="<?php echo t('PHP support for OpenSSL is required in order to safely generate encryption keys.')?>" /><?php } ?></td>
	</tr>
	</tbody>
	</table>
	
</div>
</div>

<div class="row">
<div class="span10 offset1">
<div class="well" id="install-success">
	<form method="post" action="<?php echo $this->url('/install','setup')?>">
	<input type="hidden" name="locale" value="<?php echo $locale?>" />
	<a class="btn btn-large primary" href="javascript:void(0)" onclick="$(this).parent().submit()"><?php echo t('Continue to Installation')?> <i class="icon-arrow-right icon-white"></i></a>
	</form>
</div>

<div class="block-message alert-message error" id="install-errors">
	<p><?php echo t('There are problems with your installation environment. Please correct them and click the button below to re-run the pre-installation tests.')?></p>
	<div class="block-actions">
	<form method="post" action="<?php echo $this->url('/install')?>">
	<input type="hidden" name="locale" value="<?php echo $locale?>" />
	<a class="btn" href="javascript:void(0)" onclick="$(this).parent().submit()"><?php echo t('Run Tests')?> <i class="icon-refresh"></i></a>
	</form>
	</div>	
</div>

</div>
</div>

<?php } else { ?>

<div class="row">
<div class="span10 offset1">
<div class="page-header">
	<h1><?php echo t('Install PasswordX')?></h1>
	<p><?php echo t('Version %s', APP_VERSION)?></p>
</div>
</div>
</div>

<div class="row">
<div class="span10 offset1">

<div id="ccm-install-intro">

<form method="post" action="<?php echo $this->url('/install', 'select_language')?>">
<fieldset>
	<div class="clearfix">
	
	<label for="locale"><?php echo t('Language')?></label>
	<div class="input">
		<?php echo $form->select('locale', $locales, 'en_US'); ?>
	</div>
	
	</div>
	
	<div class="actions">
	<?php echo $form->submit('submit', t('Choose Language'))?>
	</div>
</fieldset>
</form>

</div>
</div>
</div>

<?php } ?>