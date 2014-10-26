<?php

class Rippleffect_All_Helper_Http extends Mage_Core_Helper_Http {

    public function getRemoteAddr($ipToLong = false) {
        $this->_remoteAddr = Mage::helper('rippleffect')->getRemoteAddr();
        return $ipToLong ? ip2long($this->_remoteAddr) : $this->_remoteAddr;
    }

}
