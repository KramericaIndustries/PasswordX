<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); 
?>

<div class="content-header">

          <h1>
            <a id="menu-toggle" href="#" class="btn btn-default"><span class="glyphicon glyphicon-align-justify"></span></a>
            Hello world();
          </h1>
</div>

        <!-- Keep all page content within the page-content inset div! -->
        <div class="page-content inset">
			
			<div class="row">
				<div class="col-md-10">
					<p class="lead">Revealing the answer to life the universe and everything else.</p>
					<hr />
					
					<div id="mirror-results"></div>
					
					
					
					<?php //$a=new GlobalArea("Container Content"); $a->display(); ?>
					<?php $a=new GlobalArea("Subpage list"); $a->display(); ?>
				</div>
			</div>
		</div>
		
		
<script type="text/javascript">
$(function(){
	/* Cheat you fucking way out of it */
	$clone = $("#searchResults").clone();
	$("#mirror-results").append($clone);
	$("#mirror-results").children().show();
});
</script>
		
<?php 
$this->inc('elements/footer.php');
?>