<?php

class KidsCavern_Shop_Model_Observer extends Mage_Core_Model_Abstract {
    
    public function applySiteBackground($observer) {
        $block = $observer->getBlock();
        if ($block instanceof Mage_Page_Block_Html) {
            $background = Mage::getStoreConfig('kidscavern/background/background_image');
            if (!is_null($background) && $background != "") {
                $block->addBodyClass("background");
                $block->addBodyClass($background);
            }
        }
    }

}

