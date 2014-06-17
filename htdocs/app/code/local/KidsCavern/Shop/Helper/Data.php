<?php

class KidsCavern_Shop_Helper_Data extends Mage_Core_Helper_Abstract {
    
    public function getHelpUrl() {
        return $this->_getUrl('help');
    }
    
    public function getContactsUrl() {
        return $this->_getUrl('contacts');
    }
    
    public function getNewsUrl() {
        return "#";
    }
    
}
