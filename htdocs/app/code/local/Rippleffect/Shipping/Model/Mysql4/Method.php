<?php

class Rippleffect_Shipping_Model_Mysql4_Method extends Mage_Core_Model_Mysql4_Abstract {
    
    public function _construct() {
        $this->_init('rippleffect_shipping/method', 'method_id');
    }

}
