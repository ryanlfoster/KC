<?php
class Rjs_StyleIcons_Model_Resource_Entry_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct() {
        $this->_init('styleicons/entry');
    }
}