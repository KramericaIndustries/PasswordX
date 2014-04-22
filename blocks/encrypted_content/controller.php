<?php
defined('C5_EXECUTE') or die("Access Denied.");

class EncryptedContentBlockController extends Concrete5_Controller_Block_Content {

	protected $btTable = 'btEncryptedContentLocal';

	public function getBlockTypeDescription() {
		return t("HTML/WYSIWYG Editor Encrypted Content.");
	}

	public function getBlockTypeName() {
		return t("Encrypted Content");
	}
	
	/*
	 * Gets the content
	 */
	function getContent() {
		
		//encrypt this shit
		//get the encryption key
		global $u;
		$mek = $u->getMEK();
		
		//and decrypt the fields
		$crypto = Loader::helper("crypto");
		$content = $crypto->decrypt( $this->content, $mek );  
		
		//do the translation
		$content = Loader::helper('content')->translateFrom($content);
		
		//return
		return $content;
	}
	
	function getContentEditMode() {
		
		//encrypt this shit
		//get the encryption key
		global $u;
		$mek = $u->getMEK();
		
		//and decrypt the fields
		$crypto = Loader::helper("crypto");
		$content = $crypto->decrypt( $this->content, $mek );  
		
		//do the translation
		$content = Loader::helper('content')->translateFromEditMode($content);
	
		return $content;
	}
	
	
	function save($args) {
			
		$args['content'] = Loader::helper('content')->translateTo($args['content']);
		
		//encrypt this shit
		//get the encryption key
		global $u;
		$mek = $u->getMEK();
		
		//and enrypt the fields
		$crypto = Loader::helper("crypto");
		
		$args['content'] = $crypto->encrypt( $args['content'], $mek );
		
		unset($mek);
		
		parent::save($args);
	}
	
		
}
	