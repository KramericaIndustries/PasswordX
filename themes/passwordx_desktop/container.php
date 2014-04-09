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
            <?php echo $page->getCollectionName(); ?> Container
          </h1>
</div>

        <!-- Keep all page content within the page-content inset div! -->
        <div class="page-content inset">
			
			<div class="row">
				<div class="col-md-8">
					<?php $a=new GlobalArea("Container Content"); $a->display(); ?>
					<?php $a=new GlobalArea("Subpage list"); $a->display(); ?>
				</div>
			</div>
		</div>

		
	  </div> <!-- //inset -->
 	 </div> <!-- //page-content-wrapper -->
    </div> <!-- //wrapper -->
	<script type="text/javascript" src="<?php  echo $this->getThemePath(); ?>/js/main.js"></script>
	<?php $this->inc('elements/footer.php'); ?>