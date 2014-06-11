<?php
defined('C5_EXECUTE') or die("Access Denied.");

class CountryCodeAttributeTypeController extends AttributeTypeController  {
	
	/**
	 * Save the attribute in DB
	 * @param array/CountryCodeAttributeTypeValue $data
	 */
	public function saveForm($data) {
		$this->saveValue($data);
	}
	
	/**
	 * Saves a value
	 * @param array/CountryCodeAttributeTypeValue $data
	 */
	public function saveValue($data) {
		$db = Loader::db();
		
		if ($data instanceof CountryCodeAttributeTypeValue) {
			$data = (array) $data;
		}
		
		if( is_string($data) ) {
			$countryCode = $data;
		} else {
			extract($data);
		}
		
		$db->Replace('atCountryCode', array('avID' => $this->getAttributeValueID(),
				'code' => $countryCode
		),
				'avID', true
		);
	}
	
	/**
	 * Value getter
	 * @return CountryCodeAttributeTypeValue
	 */
	public function getValue() {
		$val = CountryCodeAttributeTypeValue::getByID($this->getAttributeValueID());
		return $val;
	}
	
	/**
	 * Return value for display
	 * @return string
	 */
	public function getDisplayValue() {
		$v = $this->getValue();
		return '+' . $v->code;
	}
	
	/**
	 * Load existing data from avID
	 */
	protected function load() {
		$ak = $this->getAttributeKey();
		if (!is_object($ak)) {
			return false;
		}
	}
	
	/**
	 * For typeForm
	 */
	public function type_form() {
		$this->load();
	}
	
	/**
	 * For form
	 */
	public function form() {
		$this->load();
		
		if (is_object($this->attributeValue)) {
			$value = $this->getAttributeValue()->getValue();
			$this->set('country_code', $value->code);
		}
		
	}
}

class CountryCodeAttributeTypeValue extends Object {
	
	/**
	 * Get static value
	 * @param int $avID
	 * @return CountryCodeAttributeTypeValue
	 */
	public static function getByID($avID) {
		$db = Loader::db();
		$value = $db->GetRow("select avID, name,code from atCountryCode where avID = ?", array($avID));
		
		$tmp = new CountryCodeAttributeTypeValue();
		$tmp->setPropertiesFromArray($value);
		if ($value['avID']) {
			return $tmp;
		}
	}
	
	/**
	 * To string converter
	 * @return string
	 */
	public function __toString() {
		return '' . $this->code;
	}
	
}