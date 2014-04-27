<?php defined('C5_EXECUTE') or die("Access Denied."); 
/**
 * Custom Page Not Found (404)
 * (c) 2014 PasswordX
 * Apache v2 License
 */

//If user not logged in, we want the system to behave the same as when valid page paths are hit in order to disguise that they exist.
 global $u;
 if (!$u->isLoggedIn()) {
  header("HTTP/1.1 200 OK");
  
  $v = new View;
  $login_page = Page::getByPath("/login");
  $v->setCollectionObject( $login_page );
  $v->render($login_page);
  die();
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