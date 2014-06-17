<?php

class Rippleffect_All_Block_Page_Html_Header extends Mage_Page_Block_Html_Header {
    
    /**
     * @return Mage_Sales_Model_Quote
     */
    public function getCart() {
        return Mage::getModel('checkout/session')->getQuote();
    }
    
    /**
     * @return Mage_Checkout_Model_Cart
     */
    public function getCartUrl() {
        return Mage::getUrl("checkout/cart");
    }
    
    
    public function countCartItems() {
        return sprintf("%d", $this->getCart()->getItemsQty());
    }
    public function getCartSubtotal() {
        return $this->getCart()->getBaseSubtotal();
    }
}
