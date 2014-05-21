<?php
	defined('C5_EXECUTE') or die("Access Denied.");
	$content = $controller->getContent();
	
$ci = Loader::helper('concrete/urls');
$btIcon = $ci->getBlockTypeIconURL($this->getBlockObject());
global $c;
?>

<div class="encrypted-block-contain <?php echo ($c->isEditMode() ? "editmode" : ""); ?>">
 <img src="<?php echo $btIcon; ?>" class="block-icon" />

<?php	print $content; ?>

</div>