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
          <h1><?php echo $page->getCollectionName(); ?></h1>
        </div>
		
        <!-- Keep all page content within the page-content inset div! -->
	  
				<?php 
					$a = new Area("Secrets");
					$a->display($c);
				?>

				<div class="spacer"></div>
				
				
				<?php
					if($a->getTotalBlocksInArea() == 0 ) {
				?>
				<p><span class="label label-info">Info</span> Add new information by putting the page in Edit mode and adding one of the blocks available.</p>
				<?php
						
					} 
				?>

		<!-- Need check for mobile and show different tip -->
			<hr />
			<p><span class="label label-primary">HowTo</span> Hover over the hashed area to reveal the password.</p>
			<p><span class="label label-info">Tip</span> Use Ctrl+C to copy it to clipboard. :)</p>
		
	  </div> <!-- //inset -->       
 	 </div> <!-- //page-content-wrapper -->
    </div> <!-- //wrapper -->
	<script type="text/javascript" src="<?php  echo $this->getThemePath(); ?>/js/main.js"></script>
	<?php $this->inc('elements/footer.php'); ?>