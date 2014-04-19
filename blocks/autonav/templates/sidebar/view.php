<?php  defined('C5_EXECUTE') or die("Access Denied.");

$navItems = $controller->getNavItems();

/**
 * The $navItems variable is an array of objects, each representing a nav menu item.
 * It is a "flattened" one-dimensional list of all nav items -- it is not hierarchical.
 * However, a nested nav menu can be constructed from this "flat" array by
 * looking at various properties of each item to determine its place in the hierarchy
 * (see below, for example $navItem->level, $navItem->subDepth, $navItem->hasSubmenu, etc.)
 *
 * Items in the array are ordered with the first top-level item first, followed by its sub-items, etc.
 *
 * Each nav item object contains the following information:
 *	$navItem->url        : URL to the page
 *	$navItem->name       : page title (already escaped for html output)
 *	$navItem->target     : link target (e.g. "_self" or "_blank")
 *	$navItem->level      : number of levels deep the current menu item is from the top (top-level nav items are 1, their sub-items are 2, etc.)
 *	$navItem->subDepth   : number of levels deep the current menu item is *compared to the next item in the list* (useful for determining how many <ul>'s to close in a nested list)
 *	$navItem->hasSubmenu : true/false -- if this item has one or more sub-items (sometimes useful for CSS styling)
 *	$navItem->isFirst    : true/false -- if this is the first nav item *in its level* (for example, the first sub-item of a top-level item is TRUE)
 *	$navItem->isLast     : true/false -- if this is the last nav item *in its level* (for example, the last sub-item of a top-level item is TRUE)
 *	$navItem->isCurrent  : true/false -- if this nav item represents the page currently being viewed
 *	$navItem->inPath     : true/false -- if this nav item represents a parent page of the page currently being viewed (also true for the page currently being viewed)
 *	$navItem->attrClass  : Value of the 'nav_item_class' custom page attribute (if it exists and is set)
 *	$navItem->isHome     : true/false -- if this nav item represents the home page
 *	$navItem->cID        : collection id of the page this nav item represents
 *	$navItem->cObj       : collection object of the page this nav item represents (use this if you need to access page properties and attributes that aren't already available in the $navItem object)
 */


/** For extra functionality, you can add the following page attributes to your site (via Dashboard > Pages & Themes > Attributes):
 *
 * 1) Handle: exclude_nav
 *    (This is the "Exclude From Nav" attribute that comes pre-installed with concrete5, so you do not need to add it yourself.)
 *    Functionality: If a page has this checked, it will not be included in the nav menu (and neither will its children / sub-pages).
 *
 * 2) Handle: exclude_subpages_from_nav
 *    Type: Checkbox
 *    Functionality: If a page has this checked, all of that pages children (sub-pages) will be excluded from the nav menu (but the page itself will be included).
 *
 * 3) Handle: replace_link_with_first_in_nav
 *    Type: Checkbox
 *    Functionality: If a page has this checked, clicking on it in the nav menu will go to its first child (sub-page) instead.
 *
 * 4) Handle: nav_item_class
 *    Type: Text
 *    Functionality: Whatever is entered into this textbox will be outputted as an additional CSS class for that page's nav item (NOTE: you must un-comment the "$ni->attrClass" code block in the CSS section below for this to work).
 */


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
		$classes[] = 'sidebar-brand';
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
 <a href="javascript:void(0);" class="expandall"><span class="innertext">Expand all</span> <span class="glyphicon glyphicon-collapse-down"></span></a>
</li>
<?php

$last_cid = array(); //keep a stack structure of all the parent pages

foreach ($navItems as $ni) {

	if( $ni->isHome ) {
		continue;
	}

	$page = Page::getByID( $ni->cID ); 

	if( $page->getCollectionTypeHandle() == "container" ) {
		$ni->classes .= ' nav-dropdown';
	}
	
	echo '<li class="sidebar-item ' . $ni->classes . '">'; //opens a nav item

	//Start displaying the link content
	echo '<a href="' . ( $page->getCollectionTypeHandle() == "passpack" ? $ni->url : '#' ) . '" target="' . $ni->target . '" class="' . $ni->classes . '">';
	
	//show the dropdown icons
	if( $page->getCollectionTypeHandle() == "container" && !$ni->isHome) {
		echo '<span class="sign-icon glyphicon glyphicon-' . ($ni->inPath ? 'minus' : 'plus' ). '"></span> ';
	}
	
	echo $ni->name;

	echo '</a>';
	
	echo '<span class="rename-item actions-icon sign-icon glyphicon glyphicon-pencil" alt="Rename" title="Rename" data-name="'.$ni->name.'" data-cID="'.$ni->cID.'"></span>';
	
	echo '<span class="delete-item actions-icon sign-icon glyphicon glyphicon-remove" alt="Delete" title="Delete" data-name="'.$ni->name.'" data-cID="'.$ni->cID.'"></span>';	

	if ($ni->hasSubmenu) {
		echo '<ul class="' . $ni->classes . '">'; //opens a dropdown sub-menu
		array_push($last_cid, $ni->cID); //new level begins, save cID in stack
	} else {
	
	
	echo '</li>'; //closes a nav item

		if( $page->getCollectionTypeHandle() == "container" && !$ni->isHome ) {
			echo '<li><ul class="nav-dropdown">';
			echo '<li><a class="add-item" data-parent-cid="' . $ni->cID . '" href="javascript:void(0)"><span class="glyphicon glyphicon-plus-sign"></span> Add new item</a></li>';
			echo '</ul></li>';
		}
	
		
		$depth = $ni->subDepth;
		while( $depth ) {
			$parent_category = array_pop($last_cid);
			echo '<li class="new-item"><a class="add-item" data-parent-cid="' . $parent_category . '" href="javascript:void(0)"><span class="glyphicon glyphicon-plus-sign"></span> Add new item</a></li>';
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
