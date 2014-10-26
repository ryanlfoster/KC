<?php
class JJM_Styleguide_Model_Mysql4_Products extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct() {
        $this->_init('styleguide/products', 'id');
    }
}