<?php  defined('C5_EXECUTE') or die("Access Denied.");

$navItems = $controller->getNavItems();

/*** STEP 1 of 2: Determine all CSS classes (only 2 are enabled by default, but you can un-comment other ones or add your own) ***/
foreach ($navItems as $ni) {
	$classes = array();

	if ($ni->isCurrent) {
		//class for the page currently being viewed
		$classes[] = 'nav-selected';
	}

	if ($ni->inPath) {
		//class for parent items of the page currently being viewed
		$classes[] = 'nav-path-selected';
	}

	/*
	if ($ni->isFirst) {
		//class for the first item in each menu section (first top-level item, and first item of each dropdown sub-menu)
		$classes[] = 'nav-first';
	}
	*/

	/*
	if ($ni->isLast) {
		//class for the last item in each menu section (last top-level item, and last item of each dropdown sub-menu)
		$classes[] = 'nav-last';
	}
	*/

	
	if ($ni->hasSubmenu) {
		//class for items that have dropdown sub-menus
		$classes[] = 'nav-dropdown';
	}
	

	/*
	if (!empty($ni->attrClass)) {
		//class that can be set by end-user via the 'nav_item_class' custom page attribute
		$classes[] = $ni->attrClass;
	}
	*/


	if ($ni->isHome) {
		//home page
		//$classes[] = 'sidebar-brand';
	}

	/*
	//unique class for every single menu item
	$classes[] = 'nav-item-' . $ni->cID;
	*/
	
	

	//Put all classes together into one space-separated string
	$ni->classes = implode(" ", $classes);
}

//*** Step 2 of 2: Output menu HTML ***/
global $c;
echo '<ul class="sidebar-nav ' . ($c->isEditMode() ? "sidebar-nav-edit-mode" : "") . '">'; //opens the top-level menu

?>
<li>
 <a href="javascript:void(0);" class="expandall pull-right"><span class="innertext">Expand all</span> <span class="glyphicon glyphicon-collapse-down"></span></a>
</li>

<?php

$last_cid = array(); //keep a stack structure of all the parent pages

foreach ($navItems as $ni) {

	if( $ni->isHome ) {
	?>
	<li class="sidebar-item ">
	 <a href="/" class="<?php echo ($ni->isCurrent ? "nav-selected" : ""); ?>"> Home</a>
	</li>	
	<?php
	 continue;
	}

	$page = Page::getByID( $ni->cID ); 

	if( $page->getCollectionTypeHandle() == "container" ) {
		$ni->classes .= ' nav-dropdown';
	}
	
	echo '<li class="sidebar-item ' . $ni->classes . '">'; //opens a nav item

	//Start displaying the link content
	echo '<a href="' . ( $page->getCollectionTypeHandle() == "passpack" ? $ni->url : 'javascript:void(0);' ) . '" target="' . $ni->target . '" class="' . $ni->classes . '">';
	
	//show the dropdown icons
	if( $page->getCollectionTypeHandle() == "container" && !$ni->isHome) {
		echo '<span class="sign-icon glyphicon glyphicon-' . ($ni->inPath  ? 'minus' : 'plus' ). '"></span> ';
	}
	
	echo $ni->name;

	echo '</a>';
	
	echo '<span class="rename-item actions-icon sign-icon glyphicon glyphicon-pencil" alt="Rename" title="Rename" data-name="'.$ni->name.'" data-cid="'.$ni->cID.'"></span>';
	
	echo '<span class="delete-item actions-icon sign-icon glyphicon glyphicon-remove" alt="Delete" title="Delete" data-name="'.$ni->name.'" data-cid="'.$ni->cID.'"></span>';	

	if ($ni->hasSubmenu) {
		echo '</li><ul class="' . $ni->classes . '">'; //opens a dropdown sub-menu
		array_push($last_cid, $ni->cID); //new level begins, save cID in stack
	} else {
	
	
	echo '</li>'; //closes a nav item

		if( $page->getCollectionTypeHandle() == "container" && !$ni->isHome ) {
			echo '<ul class="'. ($ni->isCurrent ? "nav-path-selected" : "") .' nav-dropdown">';
			echo '<li><a class="add-item" data-parent-cid="' . $ni->cID . '" href="javascript:void(0);"><span class="glyphicon glyphicon-plus-sign"></span> Add new item</a></li>';
			echo '</ul>';
		}
	
		
		$depth = $ni->subDepth;
		while( $depth ) {
			$parent_category = array_pop($last_cid);
			echo '<li class="new-item"><a class="add-item" data-parent-cid="' . $parent_category . '" href="javascript:void(0);"><span class="glyphicon glyphicon-plus-sign"></span> Add new item</a></li>';
			echo '</ul></li>';
			$depth--;
		}
		
	}
	
}

/* Add to top level link */
?>
<li class="new-item"><a class="add-item" data-parent-cid="1" href="javascript:void(0)"><span class="glyphicon glyphicon-plus-sign"></span> Add new item</a></li>
<?php
echo '</ul>'; //closes the top-level menu
