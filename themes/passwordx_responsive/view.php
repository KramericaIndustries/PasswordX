<?php defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Single Page pagetype
 * (c) 2014 PasswordX
 * Apache v2 License
 */

$this->inc('elements/header.php');

 if ($sidebar) {
  $this->inc('elements/sidebar.php'); 
 }
 
?>
 <?php if (!$sidebar) { ?>
	<div id="page-content-wrapper">
 <?php } ?>

<?php print $innerContent; ?>

 <?php if (!$sidebar) { ?>
	</div>
 <?php } else { ?>
	  </div> <!-- //inset -->
  	 </div> <!-- //page-content-wrapper -->
    </div> <!-- //wrapper -->
 <?php
	   }

$this->inc('elements/footer.php'); ?>