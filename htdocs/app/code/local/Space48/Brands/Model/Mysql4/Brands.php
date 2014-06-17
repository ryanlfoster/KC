<?php
class Space48_Brands_Model_Mysql4_Brands extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Constructor initialises the initial resource model.
     */
    public function _construct()
    {
        $this->_init('brands/brands','id');
    }
}