<?php

class Rippleffect_All_Block_Catalog_Featured extends Mage_Core_Block_Template {
    
    public function getFeaturedProductsCollection() {
        $cache = Mage::app()->getCache();
        $_storeId = Mage::app()->getStore()->getId();
        
        if (!$cacheArray = $cache->load("FEATURED_PRODUCTS_COLLECTION_" . $_storeId)) {
            $collection = Mage::getModel('catalog/product')->getCollection();
            /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
            $collection->addAttributeToSelect('featured_product');
            $collection->addFieldToFilter(array(
                array('attribute' => 'featured_product', 'eq' => "1"),
            ));
            
            $cacheArray = array();
            foreach ($collection as $item) {
                $item = Mage::getModel('catalog/product')->setStoreId($_storeId)->load($item->getId());
                array_push($cacheArray, $item);
            }
            $cache->save(serialize($cacheArray), "FEATURED_PRODUCTS_COLLECTION_" . $_storeId, array(), 3600);
        }
        else {
            $cacheArray = unserialize($cacheArray);
        }
        
        return $cacheArray;
    }
    
}
