<?php

class Rippleffect_SagePaySuite_Model_Session extends Ebizmarts_SagePaySuite_Model_Session {

    public function getRemoteAddr() {
        return Mage::helper('rippleffect')->getRemoteAddr();
    }

}
