<?php   defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php   Loader::element('footer_required'); ?>   

      </div> <!-- //page-content-wrapper -->
    </div> <!-- //wrapper -->
   
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
   
   <script type="text/javascript" src="<?php  echo $this->getThemePath(); ?>/js/main.js"></script>
   </body> <!-- //body i guess -->
</html>