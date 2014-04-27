<?php defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Container pagetype
 * (c) 2014 PasswordX
 * Apache v2 License
 */

$this->inc('elements/header.php'); 
$this->inc('elements/sidebar.php'); 

$page = Page::getCurrentPage();
?>

	<div class="content-header">

          <h1>
            <?php echo $page->getCollectionName(); ?>
          </h1>
	</div>
	
		<p>This is a container page. If it contains any password pages, they are listed below. <a href="javascript:void(0);" data-parent-cid="<?php echo $page->getCollectionId(); ?>" class="add-item">Click here</a> to add a new page below this one.</p>
		
		<?php
		 /* If you want an editable area in the front end, uncomment the following two lines. 
			For more information on how Areas/Themes work see http://www.concrete5.org/documentation/how-tos/designers/make-a-theme/
		 */
		 //$a = new GlobalArea("Container Page Content"); 
		 //$a->display();
		?>
		
		
		<?php
			$nav = BlockType::getByHandle('autonav');
			$nav->controller->orderBy = 'display_asc';
			$nav->controller->displayPages = 'below';
			$nav->controller->displaySubPages = 'all';
			$nav->controller->displaySubPageLevels = 'all';
			$nav->render("templates/content");
		?>
		
		<p> </p>
		<p> </p>
		<p class="small" style="text-align: center;"><span class="label label-info">Tip</span> If you want to change the content of this page, look in themes/passwordx_responsive/container.php</p>

		
	  </div> <!-- //inset -->
 	 </div> <!-- //page-content-wrapper -->
    </div> <!-- //wrapper -->
	<script type="text/javascript" src="<?php  echo $this->getThemePath(); ?>/js/main.js"></script>
	<?php $this->inc('elements/footer.php'); ?>