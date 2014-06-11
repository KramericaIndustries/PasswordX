<?php defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Landing page pagetype
 * (c) 2014 PasswordX
 * Apache v2 License
 */

$this->inc('elements/header.php'); 
$this->inc('elements/sidebar.php'); 

$v = View::getInstance();
$v->addFooterItem($html->javascript('jquery.backstretch.js'));
$backgroundImage = Loader::helper('concrete/dashboard')->getDashboardBackgroundImage();
?>
<script type="text/javascript">
	$(function() {
		<?php if ($backgroundImage->image) { ?>
		    $.backstretch("<?php echo $backgroundImage->image?>" <?php if (!$_SESSION['dashboardHasSeenImage']) { ?>,  {speed: 750}<?php } ?>);
	    <?php } ?>
	});
</script>		

	<!-- To remove or modify the top welcome message, feel free to monkey around with this -->
	<div class="content-header">
        <h1><span id="greeting_time"></span>, <?php $u = new User(); echo ucfirst($u->getUserName()); ?>!</h1>
	</div>
	<script type="text/javascript">
	 var now = new Date();
	 var hrs = now.getHours();
	 var msg = "";

	 if (hrs >  2) msg = "Watch out for werewolves"; // REALLY early
	 if (hrs >  6) msg = "Good morning";      // After 6am
	 if (hrs > 12) msg = "Good afternoon";    // After 12pm
	 if (hrs > 17) msg = "Good evening";      // After 5pm
	 if (hrs > 22 || hrs < 2) msg = "It's getting late";        // After 10pm
	 
	 $('#greeting_time').html(msg);
	</script>
	
	<?php
	 /* This is a global area as we add content to a stack at install time. You can change the name of this area, or change it to a regular area or whatever you want. */
	 $b = new GlobalArea("Welcome Tutorial"); 
	 $b->display();
	?>
	
	
	  </div> <!-- //inset -->       	
 	 </div> <!-- //page-content-wrapper -->
    </div> <!-- //wrapper -->
	
	<script type="text/javascript">
	 /* Expand all menu items when @ homepage /hacky/ */
	 $(function() {
	  window.setTimeout(function() {
	   $('.expandall').click();
	  }, 200);
	 });
	</script>
	
	<script type="text/javascript" src="<?php  echo $this->getThemePath(); ?>/js/main.js"></script>
	<?php $this->inc('elements/footer.php'); ?>