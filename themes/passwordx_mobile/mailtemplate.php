<?php defined('C5_EXECUTE') or die("Access Denied.");
/*
 Mail Pagetype for Password
 (c) Hammerti.me @ 2013
 AHJ
*/

?>
<html>
  <head>
   <title>Edit System Page</title>
   <?php  Loader::element('header_required'); ?>
    <link rel="stylesheet" media="screen" type="text/css" href="<?php echo $this->getStyleSheet('mail.css')?>" />
	<!--[if gte mso 9]>
	<style _tmplitem="187" >
	.article-content ol, .article-content ul {
	   margin: 0 0 0 24px;
	   padding: 0;
	   list-style-position: inside;
	}
	</style>
	<![endif]-->
</head>
<body>

<div style="width: 960px; margin: 20px auto;" >
   <h1>Edit Mail Template for: <?php echo $c->getCollectionName(); ?></h1>
   <strong>HTML Email Template Below</strong>
   <hr/>
   <?php
    $a = new Area('Main');
	$a->setBlockWrapperStart('<div class="mailwrapper">');
	$a->setBlockWrapperEnd('</div>');
    $a->display($c);   
   ?>  
   
   <div class="divider spacer"></div>
   <strong>Optional Text Email Template Below</strong>
   <div class="divider spacer"></div>
   <?php
    $a = new Area('MainTxt');
    $a->display($c);   
   ?>     
   
</div>
 
<?php  Loader::element('footer_required'); ?>
</body>
</html>