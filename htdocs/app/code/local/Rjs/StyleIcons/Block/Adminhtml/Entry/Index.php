<?php

class Rjs_StyleIcons_Block_Adminhtml_Entry_Index extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct() {
        $this->_controller = 'adminhtml_entry_index';
        $this->_blockGroup = 'styleicons';
        $this->_headerText = 'Style Icons';
        parent::__construct();
    }

    protected function _prepareLayout() {
        $this->setChild('grid', $this->getLayout()->createBlock($this->_blockGroup.'/'.$this->_controller.'_grid', $this->_controller.'.grid')->setSaveParametersInSession(true));
        return parent::_prepareLayout();
    }
}