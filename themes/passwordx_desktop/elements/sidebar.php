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
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Add new 
		<select class="select-picker">
			<option value="secret">secret</option>
			<option value="category">category</option>
		</select>
		</h4>
      </div>
      <div class="modal-body">
        <label for="new-name">Name: </label>
		<input type="text" id="new-name" class="form-control modal-input" placeholder="Name">
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-modal-changes">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
				$nsa = Loader::helper("nsa"); 
				$new_version = $nsa->newVersionAvailable();
				
				if( $new_version == false) {
					$nsa->easter_egg();
				} else { ?>
				<div class="alert alert-info">
				 <i class="icon-bullhorn"></i> <strong>A new version of PasswordX is available! (v<?php echo $new_version->latest_stable ?>)</strong> <?php echo $new_version->message->update_msg ?>. <a href="<?php echo $new_version->message->update_url ?>">Click here to see how to update</a>.
				</div>
				<?php } ?> 
			</small>
		</div>
      </div>

      <!-- Page content -->
      <div id="page-content-wrapper">