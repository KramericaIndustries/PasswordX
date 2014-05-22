<?php defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Encrypted content block view
 * (c) 2014 PasswordX
 * Apache v2 License
 */
 
	$content = $controller->getContent();
	
$ci = Loader::helper('concrete/urls');
$btIcon = $ci->getBlockTypeIconURL($this->getBlockObject());
global $c;
?>

<div class="panel panel-primary blockpanel <?php echo ($c->isEditMode() ? "editmode" : ""); ?>">
      <div class="panel-heading">
        <h3 class="panel-title"><img src="<?php echo $btIcon; ?>"/> <?php echo $this->getBlockObject()->getBlockTypeObject()->getBlockTypeName(); ?></h3>
      </div>
      <div class="panel-body">



<?php	print $content; ?>

	</div>
</div>