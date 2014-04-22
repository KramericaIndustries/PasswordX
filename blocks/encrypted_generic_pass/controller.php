<?php  
/**
 * Controller for the Generic Password Block
 * (c) 2014 PasswordX
 * Apache v2 License
 */
defined('C5_EXECUTE') or die("Access Denied.");

class EncryptedGenericPassBlockController extends BlockController {
	
	protected $btName = 'Encrypted Generic Password';
	protected $btDescription = '';
	protected $btTable = 'btDCEncryptedGenericPass';
	
	protected $btInterfaceWidth = "700";
	protected $btInterfaceHeight = "400";
	
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
		$content[] = $this->field_4_textarea_text;
		return implode(' - ', $content);
	}

	
	/**
	 * Encrypt the data before saving it in the DB
	 * @param array $args
	 */
	public function save($args) {
		
		//get the encryption key
		global $u;
		$mek = $u->getMEK();
		
		//and enrypt the fields
		$crypto = Loader::helper("crypto");
		
		$encrypted_fields = array(
			'field_1_textbox_text', //title
			'field_2_textbox_text', //username
			'field_3_textbox_text', //password
			'field_4_textarea_text' //notes
		);
		
		foreach( $encrypted_fields as $thisField ) {
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
		
		$encrypted_fields = array(
			'field_1_textbox_text', //title
			'field_2_textbox_text', //username
			'field_3_textbox_text', //password
			'field_4_textarea_text' //notes
		);
		
		foreach( $encrypted_fields as $thisField ) {
			$this->set( $thisField, $crypto->decrypt( $this->$thisField, $mek ) );	
		}
		
		unset($mek);
	
	}
	
	/**
	 * Data to be editted
	 */
	public function edit() {
	
		global $u;
		$mek = $u->getMEK();
		
		//and enrypt the fields
		$crypto = Loader::helper("crypto");
		
		$encrypted_fields = array(
			'field_1_textbox_text', //title
			'field_2_textbox_text', //username
			'field_3_textbox_text', //password
			'field_4_textarea_text' //notes
		);
		
		foreach( $encrypted_fields as $thisField ) {
			$this->set( $thisField, $crypto->decrypt( $this->$thisField, $mek ) );	
		}
		
		unset($mek);
		
	}
	
}