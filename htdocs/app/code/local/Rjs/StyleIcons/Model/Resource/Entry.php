<?php
class Rjs_StyleIcons_Model_Resource_Entry extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct() {
        $this->_init('styleicons/entry', 'id');
    }
}