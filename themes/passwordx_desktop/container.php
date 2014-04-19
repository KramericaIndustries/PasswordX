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
            <a id="menu-toggle" href="#" class="btn btn-default"><i class="icon-reorder"></i></a>
            <?php echo $page->getCollectionName(); ?>
          </h1>
	</div>
		<p>This is a container page. If it contains any password pages, they are listed below. Add new password pages below this container page by <a href="javascript:void(0);" data-parent-cid="<?php echo $page->getCollectionId(); ?>" class="add-item">clicking here</a></p>
		
		<?php
			$nav = BlockType::getByHandle('autonav');
			$nav->controller->orderBy = 'display_asc';
			$nav->controller->displayPages = 'below';
			$nav->controller->displaySubPages = 'all';
			$nav->controller->displaySubPageLevels = 'all';
			$nav->render("templates/content");
		?>

		
	  </div> <!-- //inset -->
 	 </div> <!-- //page-content-wrapper -->
    </div> <!-- //wrapper -->
	<script type="text/javascript" src="<?php  echo $this->getThemePath(); ?>/js/main.js"></script>
	<?php $this->inc('elements/footer.php'); ?>