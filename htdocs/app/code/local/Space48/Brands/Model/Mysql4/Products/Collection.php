<?php
class Space48_Brands_Model_Mysql4_Products_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Constructor initialises the initial collection.
     */
    public function _construct()
    {
        $this->_init('products/products');
    }
}