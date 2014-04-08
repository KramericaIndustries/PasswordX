<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); 
?>

<div class="content-header">

          <h1>
            <a id="menu-toggle" href="#" class="btn btn-default"><i class="icon-reorder"></i></a>
            Good morning, <?php $u = new User(); echo ucfirst($u->getUserName()); ?>!
          </h1>
		  
</div>

        <!-- Keep all page content within the page-content inset div! -->
        <div class="page-content inset">
			
			<div class="row">
				<div class="col-md-8">
					<?php
						$grettings = array(
							"Nice to see you again here!",
							"Long time no see!",
							"You are about to see the secrets of life, universe and everything else!",
							"Sorry, no lol cats here:(",
							"Confucius said: Never trust a cat with a broken arm!",
							"There is a chance of 10% to see this message",
							"Shhh! Don't tell NSA I am here!"
						);
					?>
					<p class="lead"><?php echo $grettings[ rand(0,(sizeof($grettings)-1)) ]; ?></p>
					<hr />
					<?php //$a=new GlobalArea("Container Content"); $a->display(); ?>
					<?php $a=new GlobalArea("Subpage list"); $a->display(); ?>
				</div>
			</div>
		</div>

<pre>
<?php
//DEBUG AREA

//var_dump( session_id() );
//var_dump( $_SESSION );

$c = Loader::helper("crypto");

var_dump($c->generateRandomString(1029,true));
echo "<hr />";
var_dump($c->generateRandomString(1029,false));

?>
</pre>
	
<?php 
$this->inc('elements/footer.php');
?>