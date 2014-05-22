<?php  
/**
 * Controller for the Virtual Host Block
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied.");

class EncryptedVhostBlockController extends BlockController {
	
	protected $btName = 'Encrypted Virtual Host';
	protected $btDescription = '';
	protected $btTable = 'btDCEncryptedVhost';
	
	protected $btInterfaceWidth = "700";
	protected $btInterfaceHeight = "580";
	
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;
	
	protected $encrypted_fields = array(
		'field_1_textbox_text', //title
		'field_2_textbox_text', //user ftp
		'field_3_textbox_text', //ftp password
		'field_4_textbox_text', //user ssh
		'field_5_textbox_text', //ssh password
		'field_6_textbox_text', //db_user
		'field_7_textbox_text', //password db
		'field_8_textbox_text', //db names
		'field_9_textarea_text', //notes
	);
	
	public function getSearchableContent() {
		$content = array();
		$content[] = $this->field_1_textbox_text;
		$content[] = $this->field_2_textbox_text;
		$content[] = $this->field_3_textbox_text;
		$content[] = $this->field_4_textbox_text;
		$content[] = $this->field_5_textbox_text;
		$content[] = $this->field_6_textbox_text;
		$content[] = $this->field_7_textbox_text;
		$content[] = $this->field_8_textbox_text;
		$content[] = $this->field_9_textarea_text;
		return implode(' - ', $content);
	}
	
	
	/**
	 * Encrypt the data before saving it in the DB
	 * @param array $args
	 */
	function save($args) {
		
		//get the encryption key
		global $u;
		$mek = $u->getMEK();
		
		//and enrypt the fields
		$crypto = Loader::helper("crypto");
		
		foreach($this->encrypted_fields as $thisField) {
			$args[ $thisField ] = $crypto->encrypt( $args[ $thisField ], $mek );
		}
		
		unset($mek);
		
		parent::save($args);
	}

	/**
	 * Decrypt the area before sending them to view
	 */
	public function view() {
	
		global $u;
		$mek = $u->getMEK();
		
		//and enrypt the fields
		$crypto = Loader::helper("crypto");
		
		foreach($this->encrypted_fields as $thisField) {
			$this->set( $thisField , $crypto->decrypt( $this->$thisField, $mek ) );
		}
		
		unset($mek);
	}
	
	/**
	 * Data to be edited
	 */
	public function edit() {
		
		global $u;
		$mek = $u->getMEK();
		
		//and enrypt the fields
		$crypto = Loader::helper("crypto");
		
		foreach($this->encrypted_fields as $thisField) {
			$this->set( $thisField , $crypto->decrypt( $this->$thisField, $mek ) );
		}
		
		unset($mek);
	}
	

}