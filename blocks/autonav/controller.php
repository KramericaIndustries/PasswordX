<?php
	defined('C5_EXECUTE') or die("Access Denied.");
	
	class AutonavBlockItem extends Concrete5_Controller_Block_AutonavItem { }
	
	class AutonavBlockController extends Concrete5_Controller_Block_Autonav {
		
		/**
		 * New and improved version of "generateNav()" function.
		 * Use this unless you need to maintain backwards compatibility with older custom templates.
		 *
		 * Pass in TRUE for the $ignore_exclude_nav arg if you don't want to exclude any pages
		 *  (for both the "exclude_nav" and "exclude_subpages_from_nav" attribute).
		 * This is useful for breadcrumb nav menus, for example.
		 * 
		 * Historical note: this must stay a function that gets called by the view templates
		 * (as opposed to just having the view() method set the variables)
		 * because we need to maintain the generateNav() function for backwards compatibility with
		 * older custom templates... and that function unfortunately has side-effects so it cannot
		 * be called more than once per request (otherwise there will be duplicate items in the nav menu).
		 */
		public function getNavItems($ignore_exclude_nav = false) {
			$c = Page::getCurrentPage();

			//Create an array of parent cIDs so we can determine the "nav path" of the current page
			$inspectC = $c;
			$selectedPathCIDs = array($inspectC->getCollectionID());
			$parentCIDnotZero=true;
			while ($parentCIDnotZero) {
				$cParentID = $inspectC->cParentID;
				if (!intval($cParentID)) {
					$parentCIDnotZero=false;
				} else {
					if ($cParentID != HOME_CID) {
						$selectedPathCIDs[] = $cParentID; //Don't want home page in nav-path-selected
					}
					$inspectC = Page::getById($cParentID, 'ACTIVE');
				}
			}
			
			//Retrieve the raw "pre-processed" list of all nav items (before any custom attributes are considered)
			$allNavItems = $this->generateNav();
			
			//Remove excluded pages from the list (do this first because some of the data prep code needs to "look ahead" in the list)
			$includedNavItems = array();
			$excluded_parent_level = 9999; //Arbitrarily high number denotes that we're NOT currently excluding a parent (because all actual page levels will be lower than this)
			$exclude_children_below_level = 9999; //Same deal as above. Note that in this case "below" means a HIGHER number (because a lower number indicates higher placement in the sitemp -- e.g. 0 is top-level)
			foreach ($allNavItems as $ni) {
				$_c = $ni->getCollectionObject();
				$current_level = $ni->getLevel();

				if ($_c->getAttribute('exclude_nav') && ($current_level <= $excluded_parent_level)) {
					$excluded_parent_level = $current_level;
					$exclude_page = true;
				} else if (($current_level > $excluded_parent_level) || ($current_level > $exclude_children_below_level)) {
					$exclude_page = true;
				} else {
					$excluded_parent_level = 9999; //Reset to arbitrarily high number to denote that we're no longer excluding a parent
					$exclude_children_below_level = $_c->getAttribute('exclude_subpages_from_nav') ? $current_level : 9999;
					$exclude_page = false;
				}

				//FIXME later
				//Exclude passpages from the list if they cannot be read
				
				/*
				 * Stefan;s crap starts here
				 */
				 
				//If it is a password page, hide it if the user cant see it 
				/*if( $_c->getCollectionTypeHandle() == "passpack" ) {
					$cp = new Permissions($_c);
					if( !$cp->canRead() ) {
						$exclude_page = true;
					}
				
				//If there is a container,...
				} elseif( $_c->getCollectionTypeHandle() == "container" ) {
					
					$visible_container = false;
					
					$child_pages = $_c->getCollectionChildrenArray(0);
					$child_pages_length = sizeof($child_pages); 
					
					//... check all the child pages and see if we can read it
					for( $i=0; $i<$child_pages_length && !$visible_container; $i++ ) {
						$cp = new Permissions( Page::getById($child_pages[$i]) );
						if( $cp->canRead() ) {
							$visible_container = true;
						}	
					}
					
					if( !$visible_container ) {
						$exclude_page = true;
					}
					
				}*/
				
				/*
				 * and ends here
				 */

				if (!$exclude_page || $ignore_exclude_nav) {
					$includedNavItems[] = $ni;
				}
			}

			//Prep all data and put it into a clean structure so markup output is as simple as possible
			$navItems = array();
			$navItemCount = count($includedNavItems);
			for ($i = 0; $i < $navItemCount; $i++) {
				$ni = $includedNavItems[$i];
				$_c = $ni->getCollectionObject();
				$current_level = $ni->getLevel();

				//Link target (e.g. open in new window)
				$target = $ni->getTarget();
				$target = empty($target) ? '_self' : $target;

				//Link URL
				$pageLink = false;
				if ($_c->getAttribute('replace_link_with_first_in_nav')) {
					$subPage = $_c->getFirstChild(); //Note: could be a rare bug here if first child was excluded, but this is so unlikely (and can be solved by moving it in the sitemap) that it's not worth the trouble to check
					if ($subPage instanceof Page) {
						$pageLink = Loader::helper('navigation')->getLinkToCollection($subPage); //We could optimize by instantiating the navigation helper outside the loop, but this is such an infrequent attribute that I prefer code clarity over performance in this case
					}
				}
				if (!$pageLink) {
					$pageLink = $ni->getURL();
				}

				//Current/ancestor page
				$selected = false;
				$path_selected = false;
				if ($c->getCollectionID() == $_c->getCollectionID()) {
					$selected = true; //Current item is the page being viewed
					$path_selected = true;
				} elseif (in_array($_c->getCollectionID(), $selectedPathCIDs)) {
					$path_selected = true; //Current item is an ancestor of the page being viewed
				}

				//Calculate difference between this item's level and next item's level so we know how many closing tags to output in the markup
				$next_level = isset($includedNavItems[$i+1]) ? $includedNavItems[$i+1]->getLevel() : 0;
				$levels_between_this_and_next = $current_level - $next_level;

				//Determine if this item has children (can't rely on $ni->hasChildren() because it doesn't ignore excluded items!)
				$has_children = $next_level > $current_level;

				//Calculate if this is the first item in its level (useful for CSS classes)
				$prev_level = isset($includedNavItems[$i-1]) ? $includedNavItems[$i-1]->getLevel() : -1;
				$is_first_in_level = $current_level > $prev_level;

				//Calculate if this is the last item in its level (useful for CSS classes)
				$is_last_in_level = true;
				for ($j = $i+1; $j < $navItemCount; $j++) {
					if ($includedNavItems[$j]->getLevel() == $current_level) {
						//we found a subsequent item at this level (before this level "ended"), so this is NOT the last in its level
						$is_last_in_level = false;
						break;
					}
					if ($includedNavItems[$j]->getLevel() < $current_level) {
						//we found a previous level before any other items in this level, so this IS the last in its level
						$is_last_in_level = true;
						break;
					}
				} //If loop ends before one of the "if" conditions is hit, then this is the last in its level (and $is_last_in_level stays true)

				//Custom CSS class
				$attribute_class = $_c->getAttribute('nav_item_class');
				$attribute_class = empty($attribute_class) ? '' : $attribute_class;

				//Page ID stuff
				$item_cid = $_c->getCollectionID();
				$is_home_page = ($item_cid == HOME_CID);


				//Package up all the data
				$navItem = new stdClass();
				$navItem->url = $pageLink;
				$navItem->name = $ni->getName();
				$navItem->target = $target;
				$navItem->level = $current_level + 1; //make this 1-based instead of 0-based (more human-friendly)
				$navItem->subDepth = $levels_between_this_and_next;
				$navItem->hasSubmenu = $has_children;
				$navItem->isFirst = $is_first_in_level;
				$navItem->isLast = $is_last_in_level;
				$navItem->isCurrent = $selected;
				$navItem->inPath = $path_selected;
				$navItem->attrClass = $attribute_class;
				$navItem->isHome = $is_home_page;
				$navItem->cID = $item_cid;
				$navItem->cObj = $_c;
				$navItems[] = $navItem;
			}
			
			return $navItems;
		}

		
		
	}
	