<?php defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Passwords/Secrets Pagetype
 * (c) 2014 PasswordX
 * Apache v2 License
 */
 
$this->inc('elements/header.php'); 
$this->inc('elements/sidebar.php'); 
$page = Page::getCurrentPage();
?>

        <div class="content-header">
          <h1><?php echo $page->getCollectionName(); ?> Secrets</h1>
        </div>
		
        <!-- Keep all page content within the page-content inset div! -->
	  
				<?php 
					$a = new Area("Secrets");
					$a->display($c);
				?>

				<div class="spacer"></div>
				
				<?php if ($c->isEditMode()) { ?>
					<hr>
				<?php } ?>
				
				<?php
					if($a->getTotalBlocksInArea() == 0 ) {
						$b = new GlobalArea("Add tutorial"); $b->display();
					} 
				?>

				<hr />
				<?php $a = new GlobalArea("howto"); $a->display();?>
		
	  </div> <!-- //inset -->       
 	 </div> <!-- //page-content-wrapper -->
    </div> <!-- //wrapper -->
	<script type="text/javascript" src="<?php  echo $this->getThemePath(); ?>/js/main.js"></script>
	<?php $this->inc('elements/footer.php'); ?>