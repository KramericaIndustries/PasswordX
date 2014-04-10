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
        <h1><span id="greeting_time"></span>, <?php $u = new User(); echo ucfirst($u->getUserName()); ?>!</h1>
	</div>
	<script type="text/javascript">
	 var now = new Date();
	 var hrs = now.getHours();
	 var msg = "";

	 if (hrs >  2) msg = "Watch out for werewolves"; // REALLY early
	 if (hrs >  6) msg = "Good morning";      // After 6am
	 if (hrs > 12) msg = "Good afternoon";    // After 12pm
	 if (hrs > 17) msg = "Good evening";      // After 5pm
	 if (hrs > 23) msg = "You should really go to bed";        // After 10pm
	 
	 $('#greeting_time').html(msg);
	</script>

    TODO: New globalarea here with Instructions default content

<pre>
<?php
//DEBUG AREA
//print_r(get_defined_constants() );

echo "<h1>Recovery</h1>";

$c = Loader::helper("crypto");

$u = new User();

$mek = $u->getMEK();
var_dump($mek);


Loader::library( "3rdparty/phpseclib/Math/BigInteger" );
Loader::library( "3rdparty/phpseclib/Crypt/RSA" );
Loader::library( "3rdparty/phpseclib/Crypt/AES" );
Loader::library( "3rdparty/phpseclib/Crypt/TripleDES" );
Loader::library( "3rdparty/phpseclib/Crypt/Random" );

$rsa = new Crypt_RSA();

$filename = DIR_BASE . '/config/recovery/recovery_key.rsa';
$handle = fopen($filename, "r");
$private_key = fread($handle, filesize($filename));
fclose($handle);

$filename = DIR_BASE . '/config/recovery/master_key';
$handle = fopen($filename, "r");
$eMEK = fread($handle, filesize($filename));
fclose($handle);

$rsa->loadKey($private_key);
$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);

$dMEK = $rsa->decrypt($eMEK); 

var_dump($dMEK);

var_dump( ( $dMEK ==  $mek ) );

//var_dump($private_key);
//var_dump($eMEK);

/*
$rsae = new Crypt_RSA();

//public key
$rsae->loadKey( $keys["publickey"] );

$plaintext = 'SUPER Secret message';

$rsae->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
$ciphertext = $rsae->encrypt($mek);
*/
?>
</pre>
end


 	 </div> <!-- //page-content-wrapper -->
    </div> <!-- //wrapper -->
	<script type="text/javascript" src="<?php  echo $this->getThemePath(); ?>/js/main.js"></script>
	<?php $this->inc('elements/footer.php'); ?>