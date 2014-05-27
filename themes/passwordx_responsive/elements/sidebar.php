<?php defined('C5_EXECUTE') or die("Access Denied."); 
/**
 * Sidebar include
 * (c) 2014 PasswordX
 * Apache v2 License
 */
?>

<div class="modal fade" id="add-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header modal-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Add new page</h4>
      </div>
      <div class="modal-body">

	 <div class="form-group">
	  <label for="select-type">Type: </label>
	  &nbsp;<select id="select-type" class="form-control select-picker">
			<option value="secret">Secrets</option>
			<option value="category">Category/Container</option>
		</select>
	 </div>	
	 
	 <div class="form-group">	  
        <label for="new-name">Name: </label>
		<input type="text" id="new-name" class="form-control" placeholder="Name">
	 </div>	
	 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-modal-changes"><i class="glyphicon glyphicon-plus"></i> Create page</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="delete-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header modal-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Delete &quot;<span id="page-name-delete"></span>&quot;</h4>
      </div>
      <div class="modal-body">
		 <p>Any subpages will also be deleted. This is permanent and cannot be undone.</p>
		
		  <div id="warn_mustconfirm" class="alert alert-danger">
		   <i class="glyphicon glyphicon-warning-sign"></i> Please enter the word &quot;DELETE&quot; exactly as displayed to confirm deletion.
		  </div>
		  
		<div class="space-6"></div>	
          <div class="row">
		   <div class="col-xs-3">
		   <strong>Confirm</strong>
		   </div>
		   <div class="col-xs-9">
		    <input class="width-80 tooltip-error" id="confirm_delete" data-rel="tooltip" data-trigger="manual" data-placement="right" title="Required" type="text" placeholder="Type the word DELETE to confirm">
		   </div>
		  </div>

		  <input type="hidden" id="delete-cid" value="" />		  
 
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
          <button type="button" id="delete_item" class="btn btn-danger">Delete page</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="rename-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header modal-primary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Rename page</h4>
      </div>
      <div class="modal-body">
		
		<input type="hidden" id="rename-cid" value="" />
		
		 <div class="form-group">
			<label for="rename-name">Enter name: </label>
			<input type="text" name="rename-name" id="rename-name" class="form-control" placeholder="">
		 </div>	
 
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" id="rename_item" class="btn btn-primary">Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">


		<form action="/index.php/searchpass/" method="get" class="ccm-search-block-form" style="" autocomplete="off">
		 <input name="search_paths[]" type="hidden" value="">
		<div class="input-group">
			<input  name="query" type="text" value="" class="form-control" id="search_input" placeholder="Search...">
		 <span class="input-group-btn">
			<button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i></button>
		 </span>	  
		</div><!-- /input-group -->	
		</form>


		<?php 
			$nav = BlockType::getByHandle('autonav');
			$nav->controller->orderBy = 'display_asc';
			$nav->controller->displayPages = 'top';
			$nav->controller->displaySubPages = 'all';
			$nav->controller->displaySubPageLevels = 'all';
			$nav->render("templates/sidebar");
		?>
		
		 <a class="manage-sitemap" href="/index.php/dashboard/sitemap/full/"><i class="glyphicon glyphicon-th-list"></i> Manage pages in Sitemap</a>
		 <div class="spacer"></div>

		</div>
        <!-- Page content -->
        <div id="page-content-wrapper">
		
			<div class="easter-egg hidden-xs hidden-mb">
				<small> 
				<?php
				
				global $u;
				$nsa = Loader::helper("nsa"); 
				$new_version = $nsa->newVersionAvailable();

				//decide what to show
				//last login message
				if( intval($u->config('SEEN_LAST_LOGIN')) == 0 ) { 
					
					//grab last login
					global $u;
					
					$ui = UserInfo::getByID( $u->getUserID() );
					$last_login_epoch = $ui->getPreviousLogin();
					
					//show only once per session
					$u->saveConfig('SEEN_LAST_LOGIN',1);

					if( $last_login_epoch ) {

						$timestamp = date(DATE_APP_GENERIC_MDY_FULL . ' \a\t ' . DATE_APP_DATE_ATTRIBUTE_TYPE_T, $last_login_epoch);
						
						$last_ip = $u->config('last_ip');
						
						$geoIp = $nsa->geoLocateIP( $last_ip );
						$location = (is_object( $geoIp ) ? sprintf( "%s, %s, %s", $geoIp->city, $geoIp->region_name, $geoIp->country_name ) : "unknown location");
						
					?>
					<div class="alert alert-info">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="margin-top: -13px; margin-right: -7px;">&times;</button>
					 Last login on <?php echo $timestamp ?>, from <?php echo $last_ip ?> (<?php echo $location ?>) <a href="/index.php/dashboard/reports/logs/" >View logs</a>
					</div>
				<?php }
				} else if( $new_version ) {
				?>
					<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="margin-top: -13px; margin-right: -7px;">&times;</button>
					 <i class="icon-bullhorn"></i> <strong>A new version of PasswordX is available! (v<?php echo $new_version->latest_stable ?>)</strong> <?php echo $new_version->message->update_msg ?>. <a href="<?php echo $new_version->message->update_url ?>">Click here to see how to update</a>.
					</div>
				<?php } else if( $u->config('permanent_easteregg_dismissal') != 1 ) { ?>
					<div class="alert alert-info">
					 <button type="button" id="easter-egg-dismissal-btn" class="close" data-dismiss="alert" aria-hidden="true" style="margin-top: -13px; margin-right: -7px;">&times;</button>
						<?php $nsa->easter_egg(); ?>
					</div>				
				<?php } ?> 
					
				</small>
			</div>
			
			<div class="page-content inset">
			
			<a id="menu-toggle" href="javascript:void(0);" class="btn btn-default"><i class="glyphicon glyphicon-list"></i></a>
			
			<?php if ($c->getCollectionId() !== "1") { //cID=1 is always home, make sure this isn't. ?>
			<div id="touch-tools" class="btn-group btn-group-sm">
			 <button type="button" class="btn btn-default rename-item" data-name="<?php echo $c->getCollectionName(); ?>" data-cid="<?php echo $c->getCollectionId(); ?>"><i class="glyphicon glyphicon-pencil"></i> Rename</button>
			 <button type="button" class="btn btn-default delete-item" data-name="<?php echo $c->getCollectionName(); ?>" data-cid="<?php echo $c->getCollectionId(); ?>"><i class="glyphicon glyphicon-remove"></i> Delete</button>
			 <?php if ($c->getCollectionTypeHandle() == "container") { ?>
			 <button type="button" class="btn btn-default add-item" data-parent-cid="<?php echo $c->getCollectionId(); ?>" ><i class="glyphicon glyphicon-plus-sign"></i> Add below</button>
			 <?php } ?>
			</div>
			<?php } ?>