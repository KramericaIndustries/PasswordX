<?php  defined('C5_EXECUTE') or die("Access Denied.");

class EncryptedVhostBlockController extends BlockController {
	
	protected $btName = 'Encrypted Virutal Host';
	protected $btDescription = '';
	protected $btTable = 'btDCEncryptedVhost';
	
	protected $btInterfaceWidth = "700";
	protected $btInterfaceHeight = "450";
	
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;
	
	protected $encrypted_fields = array('field_3_textbox_text','field_5_textbox_text','field_7_textbox_text');
	
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
	
	
	/* Encrypt the fields before doing anything actually saving it*/
	function save($args) {
		
		$cipher = new Cipher2( ENCRYPTION_KEY );
		
		foreach($this->encrypted_fields as $thisField) {
			$args[ $thisField ] = $cipher->encrypt( $args[ $thisField ]  );
		}
		
		parent::save($args);
	}

	/* Decrypt the areas for view */
	public function view() {
	
		$cipher = new Cipher2( ENCRYPTION_KEY );
		
		foreach($this->encrypted_fields as $thisField) {
			$this->set( $thisField , $cipher->decrypt( $this->$thisField ) );
		}
	}
	
	
	public function edit() {
		$cipher = new Cipher2( ENCRYPTION_KEY );
		
		foreach($this->encrypted_fields as $thisField) {
			$this->set( $thisField , $cipher->decrypt( $this->$thisField ) );
		}
	}
	

}

class Cipher2 {
    private $securekey, $iv;
    function __construct($textkey) {
	
		$this->securekey = hash('sha256',$textkey,TRUE);	
		$this->iv =mcrypt_create_iv(32,MCRYPT_DEV_URANDOM);
		
    }
    function encrypt($input) {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv));
    }
    function decrypt($input) {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->securekey, base64_decode($input), MCRYPT_MODE_ECB, $this->iv));
    }
}



