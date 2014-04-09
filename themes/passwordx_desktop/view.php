<?php defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Single Page pagetype
 * (c) 2014 PasswordX
 * Apache v2 License
 */

$this->inc('elements/header.php');
 if (!$nosidebar) {
  $this->inc('elements/sidebar.php'); 
 }
?>

<?php  print $innerContent; ?>

<?php 
 if (!$nosidebar) {
 ?>
	  </div> <!-- //inset -->
  	 </div> <!-- //page-content-wrapper -->
    </div> <!-- //wrapper -->
 <?php
 }

$this->inc('elements/footer.php'); ?>