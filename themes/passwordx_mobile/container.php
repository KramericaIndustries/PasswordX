<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); 
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

<?php 
$this->inc('elements/footer.php');
?>