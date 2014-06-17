<?php
class Space48_Brands_Model_Products extends Mage_Core_Model_Abstract
{
    /**
     * Constructor initialises the initial model.
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('products/products');
    }
}