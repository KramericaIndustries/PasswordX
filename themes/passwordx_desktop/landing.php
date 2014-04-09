<?php defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Landing page pagetype
 * (c) 2014 PasswordX
 * Apache v2 License
 */

$this->inc('elements/header.php'); 
$this->inc('elements/sidebar.php'); 
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
					<hr />
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

echo "<hr>";

echo "<h1>Key Gen</h1>";

Loader::library( "3rdparty/phpseclib/Math/BigInteger" );
Loader::library( "3rdparty/phpseclib/Crypt/RSA" );
Loader::library( "3rdparty/phpseclib/Crypt/AES" );
Loader::library( "3rdparty/phpseclib/Crypt/TripleDES" );
Loader::library( "3rdparty/phpseclib/Crypt/Random" );

$rsa = new Crypt_RSA();
$keys = $rsa->createKey(4096);

var_dump( $keys );

echo "<hr>";

echo "<h1>Encrypt</h1>";

$rsae = new Crypt_RSA();

//public key
$rsae->loadKey( $keys["publickey"] );

$plaintext = 'SUPER Secret message';

$rsae->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
$ciphertext = $rsae->encrypt($plaintext);

var_dump($ciphertext);

echo "<hr>";

echo "<h1>Decrypt</h1>";

$rsad = new Crypt_RSA();

//public key
$rsad->loadKey( $keys["privatekey"] );
$rsad->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);

$decr = $rsad->decrypt($ciphertext);

var_dump($decr);

echo "<hr>";

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


 	 </div> <!-- //page-content-wrapper -->
    </div> <!-- //wrapper -->
	<script type="text/javascript" src="<?php  echo $this->getThemePath(); ?>/js/main.js"></script>
	<?php $this->inc('elements/footer.php'); ?>