<?php  defined('C5_EXECUTE') or die("Access Denied.");

class EncryptedFancyShitRrrrBlockController extends BlockController {
	
	protected $btName = 'fancy shit rrrr';
	protected $btDescription = 'fdsfds';
	protected $btTable = 'btDCEncryptedFancyShitRrrr';
	
	protected $btInterfaceWidth = "700";
	protected $btInterfaceHeight = "450";
	
	private $mek = null;
	private $crypto = null;
	
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;

	protected $all_fields = array('field_1_textbox_text','field_2_textbox_text','field_3_textarea_text');

	protected $encrypted_fields = array('field_1_textbox_text','field_2_textbox_text','field_3_textarea_text');

	
	public function on_start() {
	
		global $u;
		$this->mek = $u->getMEK();
	
		$this->crypto = Loader::helper("crypto");
	
		parent::on_start();
	}

	public function getSearchableContent() {
		$content = array();
		$content[] = $this->field_1_textbox_text;
		$content[] = $this->field_3_textarea_text;
		return implode(' - ', $content);
	}


	/**
	 * Decrypt the area before sending them to view
	 */
	public function view() {
	
		foreach($this->all_fields as $thisField) {
			if( in_array($thisField, $this->encrypted_fields) ) {
				$this->set( $thisField , $this->crypto->decrypt( $this->$thisField, $this->mek ) );
			} else {
				$this->set( $thisField , $this->$thisField );
			}
		}
	
	}
	
	/**
	 * Encrypt the data if needed before saving it in the DB
	 * @param array $args
	 */
	function save($args) {
	
		global $u;
		$this->mek = $u->getMEK();
		$this->crypto = Loader::helper("crypto");
	
		foreach($this->encrypted_fields as $thisField) {
			$args[ $thisField ] = $this->crypto->encrypt( $args[ $thisField ], $this->mek );
		}
	
		parent::save($args);
	}
	
	/**
	 * Data to be editted
	 */
	public function edit() {
		foreach($this->all_fields as $thisField) {
			if( in_array($thisField, $this->encrypted_fields) ) {
				$this->set( $thisField , $this->crypto->decrypt( $this->$thisField, $this->mek ) );
			} else {
				$this->set( $thisField , $this->$thisField );
			}
		}
	}


}
