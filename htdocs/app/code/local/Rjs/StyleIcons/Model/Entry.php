<?php
class Rjs_StyleIcons_Model_Entry extends Mage_Core_Model_Abstract
{
	public function __construct() {
		$this->_init('styleicons/entry');
	}

	public function getImage() {
		$image = explode('/',$this->image);
		return 'media/'.end($image);
	}
}