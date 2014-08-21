<?php
class JJM_Styleguide_Model_Mysql4_Products_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct() {
        $this->_init('styleguide/products');
    }
}