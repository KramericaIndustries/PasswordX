<?php  
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); 

$html = Loader::helper('html');
?>

<h1>Stranger, you should not be here</h1>

<p class="red sanity">CSS SANITY</p> 

<p id="sanity"> <a href="#">jQuery Sanity</a> </p>

<?php  
        $a = new Area('Main');
        $a->display($c);
?>


<script type="text/javascript" src="<?php  echo $this->getThemePath(); ?>/js/main.js"></script>

<?php 
$this->inc('elements/footer.php');
?>