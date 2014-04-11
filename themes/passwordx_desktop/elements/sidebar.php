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
        <h4 class="modal-title">Add new element</h4>
      </div>
      <div class="modal-body">

	 <div class="alert alert-info">
	  <div><strong>A Secrets page</strong> is a page where you can add your passwords and other content.</div>
	  <div><strong>A Category page</strong> is a container (folder, if you will) which is used to organize your Secrets pages by, for example, client or type.</div>
	 </div>
	 
	  <form>
	 <div class="form-group">
	  <label for="select-type">Type: </label>
	  &nbsp;<select id="select-type" class="form-control select-picker">
			<option value="secret">Secrets page</option>
			<option value="category">Category/Container page</option>
		</select>
	 </div>	
	 
	 <div class="form-group">	  
        <label for="new-name">Name: </label>
		<input type="text" id="new-name" class="form-control" placeholder="Name">
	 </div>	
	 </form>
	 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-modal-changes"><i class="glyphicon glyphicon-plus"></i> Create page</button>
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
			<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
		 </span>	  
		</div><!-- /input-group -->	
		</form>


		<?php 
			$nav = BlockType::getByHandle('autonav');
			$nav->controller->orderBy = 'display_asc';
			$nav->controller->displayPages = 'top';
			$nav->controller->displaySubPages = 'all';
			$nav->controller->displaySubPageLevels = 'all';
			$nav->render();
		?>

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
					$last_login = Log::getLastLogin();
					
					//C5 way of displaying date
					$dh = Loader::helper('date');
					if (date('m-d-y') == date('m-d-y', strtotime($last_login->getTimestamp('user')))) {
                            $timestamp = t(/*i18n %s is a time*/'today at %s', $dh->date(DATE_APP_GENERIC_TS, strtotime($last_login->getTimestamp('user'))));
					} else {
                            $timestamp = $dh->date(DATE_APP_GENERIC_MDYT, strtotime($last_login->getTimestamp('user')));
                    }
					
					//show only once
					$u->saveConfig('SEEN_LAST_LOGIN',1);
					?>
					<div class="alert alert-info">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="margin-top: -13px; margin-right: -7px;">&times;</button>
					 <?php echo $last_login->getText()?>, <?php echo $timestamp ?>.
					</div>
				<?php } else if( $new_version == false) {
					$nsa->easter_egg();
				} else { ?>
					<div class="alert alert-info">
					 <i class="icon-bullhorn"></i> <strong>A new version of PasswordX is available! (v<?php echo $new_version->latest_stable ?>)</strong> <?php echo $new_version->message->update_msg ?>. <a href="<?php echo $new_version->message->update_url ?>">Click here to see how to update</a>.
					</div>
				<?php } ?> 
				</small>
			</div>
			
			<div class="page-content inset">
			
			<a id="menu-toggle" href="javascript:void(0);" class="btn btn-default"><i class="glyphicon glyphicon-list"></i></a>