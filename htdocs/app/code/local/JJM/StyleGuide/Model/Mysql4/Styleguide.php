<?php
class JJM_Styleguide_Model_Mysql4_Styleguide extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("styleguide/styleguide", "styleguide_id");
    }
}