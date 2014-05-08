<?php  defined('C5_EXECUTE') or die("Access Denied.");

class [[[GENERATOR_REPLACE_CLASSNAME]]] extends BlockController {
	
	protected $btName = '[[[GENERATOR_REPLACE_NAME]]]';
	protected $btDescription = '[[[GENERATOR_REPLACE_DESCRIPTION]]]';
[[[GENERATOR_REPLACE_TABLEDEF]]]
	protected $btInterfaceWidth = "700";
	protected $btInterfaceHeight = "450";
	
	private $mek = null;
	private $crypto = null;
	
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;

[[[GENERATOR_REPLACE_ALL_FIELDS]]]
[[[GENERATOR_REPLACE_ENCRYPTED_FIELDS]]]
	
	public function on_start() {
	
		global $u;
		$this->mek = $u->getMEK();
	
		$this->crypto = Loader::helper("crypto");
	
		parent::on_start();
	}

[[[GENERATOR_REPLACE_GETSEARCHABLECONTENT]]]

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

[[[GENERATOR_REPLACE_CONTENTHELPER]]]
}
