<?php  defined('C5_EXECUTE') or die("Access Denied.");

class EncryptedGenericPassBlockController extends BlockController {
	
	protected $btName = 'Encrypted Generic Password';
	protected $btDescription = '';
	protected $btTable = 'btDCEncryptedGenericPass';
	
	protected $btInterfaceWidth = "700";
	protected $btInterfaceHeight = "500";
	
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

	/* Encrypt the fields before doing anything actually saving it*/
	function save($args) {
		
		$cipher = new Cipher( ENCRYPTION_KEY );
		$args['field_3_textbox_text'] = $cipher->encrypt( $args['field_3_textbox_text'] );
		$args['field_2_textbox_text'] = $cipher->encrypt( $args['field_2_textbox_text']  );
		
		parent::save($args);
	}

	/* Decrypt the areas for view */
	public function view() {
	
		$cipher = new Cipher( ENCRYPTION_KEY );
		
		$this->set("field_2_textbox_text", $cipher->decrypt( $this->field_2_textbox_text ) );
		$this->set("field_3_textbox_text", $cipher->decrypt( $this->field_3_textbox_text ) );
		
	}
	
	public function edit() {
	
		$cipher = new Cipher( ENCRYPTION_KEY );
		
		$this->set("field_2_textbox_text", $cipher->decrypt( $this->field_2_textbox_text ) );
		$this->set("field_3_textbox_text", $cipher->decrypt( $this->field_3_textbox_text ) );
	}
	
}

class Cipher {
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
