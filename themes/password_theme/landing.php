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

$c = Loader::helper("crypto");

$u = new User();

$chiper = "buDa4JadECyhFEnUIdJIlyX0rgG4Z0/jbwGUqSWi2YzJC/D0cOjK8tUAEEa53JOUsgVjCL/CQvbXFajFXz/Fd5jEbdUZqs3E7CBxH9iZpVkaronQ3HI1UaxrM84bKlq92Bxf2tMNIj6R+ChsaK8jR6uvIkcYP4IEw+NJWft8ULc=:7bba46437bffef9157dc81b089e32b692a05500a";

echo "<h1>Handling Master Key</h1>";
$mek = $u->getMEK();

var_dump( $c->decrypt($chiper,$mek) );

/*$correct_uek = $c->computeUEK("baconipsum");



echo "<h1>Planting Seeds</h1>";

$planted = $u->plantSessionToken();
$got = $u->getSessionToken();

var_dump( $planted );
var_dump( $got );
var_dump( $planted == $got );

echo "<h1>Handling UEK</h1>";
$u->saveSessionUEK( $correct_uek );
$ret_uek = $u->getUEK();

var_dump($correct_uek);
var_dump( $ret_uek );
var_dump($correct_uek == $ret_uek );

echo "<h1>Handling Master Key</h1>";*/

//var_dump($u->sanity());

/*var_dump($c->generateRandomString(1029,true));
echo "<hr />";
var_dump($c->generateRandomString(1029,false));*/

?>
</pre>
end
<?php 
$this->inc('elements/footer.php');
?>