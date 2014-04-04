<?php   
defined('C5_EXECUTE') or die("Access Denied."); 
?>
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE?>">
   
   <head>  
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta charset="UTF-8">
		<meta name="description" content="Password management system" />
		<meta name="author" content="Hammerti.me">
          
        <?php   Loader::element('header_required'); ?>
	
		<!-- //CSS -->
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" media="screen" type="text/css" href="<?php  echo $this->getStyleSheet('css/main.css')?>" />
        <link rel="stylesheet" media="screen" type="text/css" href="<?php  echo $this->getThemePath(); ?>/typography.css" />
	
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	
	</head>
<body>

 <div id="wrapper">
      
      <!-- Sidebar -->
	  <div id="cover-up-sidebar-wrapper"></div>
      <div id="sidebar-wrapper">
		<p class="site-name">HammerPass</p>
		<?php $a=new GlobalArea("Search Bar"); $a->display(); ?>
		<?php $a=new GlobalArea("Password Navigator"); $a->display(); ?>
		
		<div class="easter-egg">
			<small> 
				<?php 
				$start_date = new DateTime('2013-10-26');
				$now_date = new DateTime('now');
				$uptime = $now_date->diff($start_date);
				$uptime_days = $uptime->y * 365 + $uptime->m * 31 + $uptime->d;
				?>
				This site is proudly NSA free for <?php echo $uptime_days; ?> days, <?php echo $uptime->h; ?> hours, <?php echo $uptime->i; ?> minutes and <?php echo $uptime->s ?> seconds. 
			</small>
		</div>
      </div>

      <!-- Page content -->
      <div id="page-content-wrapper">