<?php

class Rippleffect_Shipping_Model_Mysql4_Method_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
    
    public function _construct() {
        parent::_construct();
        $this->_init('rippleffect_shipping/method');
    }

}
