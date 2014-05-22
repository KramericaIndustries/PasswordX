<?php defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Content/Password search results
 * (c) 2014 PasswordX
 * Apache v2 License
 */
?>
		<h1>Search results</h1>
		<hr />
					
		<?php
		 // Include search block programmatically to process our search
		$bt = BlockType::getByHandle('search');
		$bt->render('view');
		
		global $sidebar;
		$sidebar = true;
		?>
		
	<script type="text/javascript" src="<?php  echo $this->getThemePath(); ?>/js/main.js"></script>