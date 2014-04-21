<?php defined('C5_EXECUTE') or die("Access Denied."); 
/**
 * Custom Page Not Found (404)
 * (c) 2014 PasswordX
 * Apache v2 License
 */

//This results in a 302 status code 
//- we need to have the system return the same code when hitting a valid page path in order to disguise real pages
 global $u;
 if (!$u->isLoggedIn()) {
  header("Location: /index.php/login");
 }

?>

<h1 class="error"><?php echo t('Page Not Found')?></h1>

<?php echo t('No page could be found at this address.')?>

<?php if (is_object($c)) { ?>
	<br/><br/>
	<?php $a = new Area("Main"); $a->display($c); ?>
<?php } ?>

<br/><br/>

<a href="<?php echo DIR_REL?>/"><?php echo t('Back to Home')?></a>.