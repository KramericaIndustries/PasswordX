<?php defined('C5_EXECUTE') or die("Access Denied.");
/**
 * Simplified Page Attribute editing - used in customizing login page
 * (c) 2014 PasswordX
 * Apache v2 License
 */
 
  global $u;
 if (!$u->isLoggedIn()) {
  die("Access Denied.");
 }
 
$c = Page::getByID($_REQUEST["cID"]);
$pk = PermissionKey::getByHandle('edit_page_properties');
$pk->setPermissionObject($c);
$asl = $pk->getMyAssignment(); 
?>
<div class="ccm-pane-controls ccm-ui">
<?php  if ($approveImmediately) { ?>
	<div class="alert-message block-message notice">
		<?php echo t("Note: Since you haven't checked this page out for editing, these changes will immediately be approved.")?>
	</div>
<?php  } ?>

<form method="post" name="permissionForm" id="ccmMetadataForm" action="<?php echo $c->getCollectionAction()?>">
<input type="hidden" name="approveImmediately" value="<?php echo $approveImmediately?>" />
<input type="hidden" name="rel" value="<?php echo $_REQUEST['rel']?>" />

	<script type="text/javascript"> 
		
		function ccm_triggerSelectUser(uID, uName) {
			$('#ccm-uID').val(uID);
			$('#ccm-uName').html(uName);
		}
		
		
		var ccm_activePropertiesTab = "ccm-properties-standard";
		
		$("#ccm-properties-tabs a").click(function() {
			$("li.active").removeClass('active');
			$("#" + ccm_activePropertiesTab + "-tab").hide();
			ccm_activePropertiesTab = $(this).attr('id');
			$(this).parent().addClass("active");
			$("#" + ccm_activePropertiesTab + "-tab").show();
		});
		
		$(function() {
			$("#ccmMetadataForm").ajaxForm({
				type: 'POST',
				iframe: true,
				beforeSubmit: function() {
					jQuery.fn.dialog.showLoader();
				},
				success: function(r) {
					try {
						var r = eval('(' + r + ')');
						jQuery.fn.dialog.hideLoader();
						jQuery.fn.dialog.closeTop();
						if (r != null && r.rel == 'SITEMAP') {
							ccmSitemapHighlightPageLabel(r.cID, r.name);
						} else {
							ccm_mainNavDisableDirectExit();
						}
						ccmAlert.hud(ccmi18n.savePropertiesMsg, 2000, 'success', ccmi18n.properties);
					} catch(e) {
						alert(r);
					}
				}
			});
		});
	</script>
	
	<div id="ccm-required-meta">
	<div id="ccm-properties-custom-tab">
	 <?php  Loader::element('collection_metadata_fields', array('c'=>$c, 'assignment' => $asl) ); ?>
	</div>
	<input type="hidden" name="update_metadata" value="1" />
	<input type="hidden" name="processCollection" value="1">
	<div class="ccm-spacer">&nbsp;</div>
</form>
</div>
	<div class="dialog-buttons">
	<a href="javascript:void(0)" onclick="jQuery.fn.dialog.closeTop();" class="ccm-button-left btn"><?php echo t('Cancel')?></a>
	<a href="javascript:void(0)" class="btn primary ccm-button-right" onclick="$('#ccmMetadataForm').submit()"><?php echo t('Save')?></a>
	</div>