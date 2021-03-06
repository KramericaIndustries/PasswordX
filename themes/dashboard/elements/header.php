<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php 
if ($_GET['_ccm_dashboard_external']) {
	return;
}

if (!defined('WHITE_LABEL_DASHBOARD_BACKGROUND_SRC')) {
	Config::getOrDefine('WHITE_LABEL_DASHBOARD_BACKGROUND_SRC', false);
}

if (!defined('WHITE_LABEL_DASHBOARD_BACKGROUND_CAPTION')) {
	Config::getOrDefine('WHITE_LABEL_DASHBOARD_BACKGROUND_CAPTION', false);
}

if (!defined('WHITE_LABEL_DASHBOARD_BACKGROUND_FEED')) {
	Config::getOrDefine('WHITE_LABEL_DASHBOARD_BACKGROUND_FEED', false);
}

Loader::block('autonav');
$nh = Loader::helper('navigation');
$dashboard = Page::getByPath("/dashboard");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="robots" content="noindex">
	<meta name="googlebot" content="noindex">
<?php
Loader::library('3rdparty/mobile_detect');
$md = new Mobile_Detect();

$html = Loader::helper('html');
$v = View::getInstance();
if (!isset($enableEditing) || $enableEditing == false) {
	$v->disableEditing();
}

// Required JavaScript

$v->addFooterItem($html->javascript('jquery.backstretch.js'));
$v->addFooterItem($html->javascript('jquery.ui.js'));
$v->addFooterItem($html->javascript('jquery.form.js'));
$v->addFooterItem($html->javascript('jquery.rating.js'));
$v->addFooterItem('<script type="text/javascript" src="' . REL_DIR_FILES_TOOLS_REQUIRED . '/i18n_js"></script>'); 
$v->addFooterItem($html->javascript('bootstrap.js'));
$v->addFooterItem($html->javascript('ccm.app.js'));
$v->addFooterItem($html->javascript('ccm.dashboard.js'));
$v->addFooterItem(Loader::helper('html')->javascript('tiny_mce/tiny_mce.js'));

if (ENABLE_PROGRESSIVE_PAGE_REINDEX && Config::get('DO_PAGE_REINDEX_CHECK')) {
	$v->addFooterItem('<script type="text/javascript">$(function() { ccm_doPageReindexing(); });</script>');
}

if (LANGUAGE != 'en') {
	$v->addFooterItem($html->javascript('i18n/ui.datepicker-'.LANGUAGE.'.js'));
}

// Require CSS
$v->addHeaderItem($html->css('ccm.app.css'));
if ($md->isMobile() == true) {
	$v->addHeaderItem('<meta name="viewport" content="width=device-width,initial-scale=1"/>');
	$v->addHeaderItem($html->css('ccm.app.mobile.css')); ?>
	<?php		
}
$v->addHeaderItem($html->css('ccm.dashboard.css'));
$v->addHeaderItem($html->css('jquery.ui.css'));

$valt = Loader::helper('validation/token');
$disp = '<script type="text/javascript">'."\n";
$disp .=  "var CCM_SECURITY_TOKEN = '" . $valt->generate() . "';"."\n";
$disp .= "</script>"."\n";
//require(DIR_FILES_ELEMENTS_CORE . '/header_required.php'); 
$v->addHeaderItem($disp);
Loader::element('header_required', array('disableTrackingCode' => true));
$backgroundImage = Loader::helper('concrete/dashboard')->getDashboardBackgroundImage();

//PasswordX overrides
echo $html->css('/css/passwordx.css');
?>
<script type="text/javascript">

	$(function() {
		<?php if ($backgroundImage->image) { ?>
		    $.backstretch("<?php echo $backgroundImage->image?>" <?php if (!$_SESSION['dashboardHasSeenImage']) { ?>,  {speed: 750}<?php } ?>);
	    <?php } ?>
	    <?php if ($backgroundImage->checkData) { ?>
		    ccm_getDashboardBackgroundImageData('<?php echo $backgroundImage->filename?>', <?php if ($backgroundImage->displayCaption) { ?> true <?php } else { ?> false <?php } ?>);
		<?php } ?>

		$(window).on('resize', function() {
			ccm_testFixForms();
		});
		$('form').each(function() {
			$(this).attr('original-class', $(this).attr('class'));
		});
		ccm_testFixForms();
	});
</script>

</head>
<body>

<?php if (!$_SESSION['dashboardHasSeenImage']) { 
	$_SESSION['dashboardHasSeenImage'] = true;
} ?>

<?php if (isset($backgroundImage->caption) && $backgroundImage->caption) { ?>
	<div id="ccm-dashboard-background-caption" class="ccm-ui"><div id="ccm-dashboard-background-caption-inner"><?php if ($backgroundImage->url) { ?><a target="_blank" href="<?php echo $backgroundImage->url?>"><?php } ?><?php echo $backgroundImage->caption?><?php if ($backgroundImage->url) { ?></a><?php } ?></div></div>
<?php } ?>

<div class="ccm-ui">

<div id="ccm-toolbar">
<ul id="ccm-main-nav">
<li id="ccm-logo-wrapper"><?php echo Loader::helper('concrete/interface')->getToolbarLogoSRC()?></li>
<li><a class="ccm-icon-back ccm-menu-icon" href="<?php echo $this->url('/')?>"><?php if ($md->isMobile()) { ?><?php echo t('Back')?><?php } else { ?><?php echo t('Return to PasswordX')?><?php } ?></a></li>

<!-- Concrete5 gets a prominent mention in our custom Dashboard footer 

<?php if (Loader::helper('concrete/interface')->showWhiteLabelMessage()) { ?>
	<li id="ccm-white-label-message"><?php echo t('Powered by <a href="%s">concrete5</a>.', CONCRETE5_ORG_URL)?></li>
<?php } ?>

-->

</ul>

<ul id="ccm-system-nav">

<li class="hidden-xs"><a class="ccm-icon-edit-external-link ccm-menu-icon" id="ccm-nav-report" target="_blank" href="<?php echo GITHUB_ISSUES_URL; ?>">Suggestions / Bugs</a></li>

<li><a class="ccm-icon-dashboard ccm-menu-icon" id="ccm-nav-dashboard<?php if ($md->isMobile()) { ?>-mobile<?php } ?>" href="<?php echo $this->url('/dashboard/passwordx/administration')?>"><?php echo t('Dashboard')?></a></li>

<!-- Disabling the "intelligent search" here - we might bring this back if it makes sense.
<li id="ccm-nav-intelligent-search-wrapper"><input type="search" placeholder="<?php echo t('Intelligent Search')?>" id="ccm-nav-intelligent-search" tabindex="1" /></li>-->

<?php if ($md->isMobile() == false) { ?>
	<li><a id="ccm-nav-sign-out" class="ccm-icon-sign-out ccm-menu-icon" href="<?php echo $this->url('/login', 'logout')?>"><?php echo t('Sign Out')?></a></li>
<?php } ?>
</ul>

</div>
<?php
/* Commented out in order to disable hover on Dashboard button
$_ih = Loader::helper('concrete/interface');
$dh = Loader::helper('concrete/dashboard');
$html = $dh->getDashboardAndSearchMenus();
print $dh->addQuickNavToMenus($html);
*/
?>
</div>
<div id="ccm-dashboard-page">

<div id="ccm-dashboard-content">

	<div class="container">

	<?php 
	$nsa = Loader::helper("nsa"); 
	$new_version = $nsa->newVersionAvailable();
	
	if( $new_version != false ) { ?>
	<!-- id="ccm-dashboard-result-message" -->
		<div class="ccm-ui">
			<div class="row">
				<div class="span10 offset1">
				<div class="alert alert-info">
					<i class="icon-bullhorn"></i> A new version of PasswordX is available! (v<?php echo $new_version->latest_stable ?>) <?php echo $new_version->message->update_msg ?>. <a href="<?php echo $new_version->message->update_url ?>">Click here to see how to update</a>.
				</div>
				</div>
			</div>
		</div>
	<?php } ?>
	
	<?php if (isset($error)) { ?>
		<?php 
		if ($error instanceof Exception) {
			$_error[] = $error->getMessage();
		} else if ($error instanceof ValidationErrorHelper) {
			$_error = array();
			if ($error->has()) {
				$_error = $error->getList();
			}
		} else {
			$_error = $error;
		}
		
		if (count($_error) > 0) {
			?>
			<div class="ccm-ui"  id="ccm-dashboard-result-message">
				<?php Loader::element('system_errors', array('format' => 'block', 'error' => $_error)); ?>
			</div>
		<?php 
		}
	}
	
	if (isset($message)) { ?>
		<div class="ccm-ui" id="ccm-dashboard-result-message">
			<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">×</button><?php echo nl2br(Loader::helper('text')->entities($message))?></div>
		</div>
	<?php 
	} else if (isset($success)) { ?>
		<div class="ccm-ui" id="ccm-dashboard-result-message">
			<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><?php echo nl2br(Loader::helper('text')->entities($success))?></div>
		</div>
	<?php } ?>