<?php

/**
 * Like adminhtml/system_config_source_yesno, but "before" and "after"
 *
 * @category   Rippleffect
 * @package    Rippleffect_All
 * @author     Dan Jones <djones@rippleffect.com>
 */
class Rippleffect_All_Model_Adminhtml_Beforeafter {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        return array(
            array('value' => 'before', 'label' => Mage::helper('adminhtml')->__('Before')),
            array('value' => 'after', 'label' => Mage::helper('adminhtml')->__('After')),
        );
    }

}