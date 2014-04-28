<?php  defined('C5_EXECUTE') or die("Access Denied.");

class FancyShittBlockController extends BlockController {
	
	protected $btName = 'fancy shitt';
	protected $btDescription = 'fs2';
	protected $btTable = 'btDCFancyShitt';
	
	protected $btInterfaceWidth = "700";
	protected $btInterfaceHeight = "450";
	
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;
	
	public function getSearchableContent() {
		$content = array();
		$content[] = $this->field_1_textbox_text;
		$content[] = $this->field_2_textbox_text;
		$content[] = $this->field_3_textbox_text;
		return implode(' - ', $content);
	}








}
