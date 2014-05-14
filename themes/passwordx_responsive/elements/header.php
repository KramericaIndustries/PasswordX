<?php defined('C5_EXECUTE') or die("Access Denied."); 
/**
 * Header template include
 * (c) 2014 PasswordX
 * Apache v2 License
 */
?>
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE?>">
 <head>  

<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta charset="UTF-8">
<meta name="robots" content="noindex">
<meta name="googlebot" content="noindex">
<meta name="description" content="Password management system" />
<meta name="author" content="PasswordX.io">
<meta name="viewport" content="width=device-width, initial-scale=1">
          
<?php   Loader::element('header_required'); ?>
	
<!-- //CSS -->
<link href="<?php  echo $this->getThemePath(); ?>/frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" media="screen" type="text/css" href="<?php  echo $this->getStyleSheet('main.css')?>" />
<link rel="stylesheet" media="screen" type="text/css" href="<?php  echo $this->getThemePath(); ?>/typography.css" />
	
 </head>
<body>