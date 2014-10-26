<?php

class KidsCavern_Shop_Model_System_Config_Source_Background {

    public function toOptionArray() {
        return array(
            array('value' => '', 'label' => Mage::helper('kidscavern')->__("None")),
            array('value' => 'spring', 'label' => Mage::helper('kidscavern')->__('Spring')),
            array('value' => 'summer', 'label' => Mage::helper('kidscavern')->__('Summer')),
            array('value' => 'autumn', 'label' => Mage::helper('kidscavern')->__('Autumn')),
            array('value' => 'winter', 'label' => Mage::helper('kidscavern')->__('Winter')),
            array('value' => 'sale', 'label' => Mage::helper('kidscavern')->__('Sale')),
            array('value' => 'halloween', 'label' => Mage::helper('kidscavern')->__('Halloween')),
        );
  }

}

