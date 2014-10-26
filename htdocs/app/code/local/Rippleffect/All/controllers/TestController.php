<?php

class Rippleffect_All_TestController extends Mage_Core_Controller_Front_Action {
    
    public function menuAction() {
        echo "<pre>";
        
        $menu = Mage::getBlockSingleton('catalog/navigation');
        echo get_class($menu);
        
        
        exit;
    }
    
}