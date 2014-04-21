<?php defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Landing page pagetype
 * (c) 2014 PasswordX
 * Apache v2 License
 */

$this->inc('elements/header.php'); 
$this->inc('elements/sidebar.php'); 
?>

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
	 $b = new GlobalArea("Welcome Tutorial"); 
	 $b->display();
	?>
	
 	 </div> <!-- //page-content-wrapper -->
    </div> <!-- //wrapper -->
	<script type="text/javascript" src="<?php  echo $this->getThemePath(); ?>/js/main.js"></script>
	<?php $this->inc('elements/footer.php'); ?>