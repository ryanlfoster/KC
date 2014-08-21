<?php

class JJM_Styleguide_Model_Styleguide extends Mage_Core_Model_Abstract
{
    protected function _construct(){
       $this->_init("styleguide/styleguide");
    }

    public function getCustomProducts() {

        $products = Mage::getModel('styleguide/products')->getCollection()->addFieldToFilter('styleguide_id',$this->getStyleguideId());

        $result = array();
        foreach($products as $product) {
            try {
                $result[] = Mage::getModel('catalog/product')->load($product->getProductId());

            } catch(Exception $e) {
//                var_dump($e->getMessage());
            }
        }

        return $result;
    }

}
	 