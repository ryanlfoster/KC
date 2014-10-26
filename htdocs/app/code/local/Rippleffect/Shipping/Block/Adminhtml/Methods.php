<?php

class Rippleffect_Shipping_Block_Adminhtml_Methods extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_methods';
        $this->_blockGroup = 'rippleffect_shipping';
        $this->_headerText = Mage::helper('rippleffect_shipping')->__('Shipping Methods');
        $this->_addButtonLabel = Mage::helper('rippleffect_shipping')->__('Add Method');

        parent::__construct();
    }
    
}
