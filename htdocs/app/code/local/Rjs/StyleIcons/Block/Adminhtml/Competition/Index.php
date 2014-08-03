<?php

class Rjs_StyleIcons_Block_Adminhtml_Competition_Index extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct() {
        $this->_controller = 'adminhtml_competition_index';
        $this->_blockGroup = 'styleicons';
        $this->_headerText = 'Style Icons';
        parent::__construct();

        if(Mage::getModel('styleicons/competition')->getCollection()->addFieldToFilter('active',1)->getSize() > 0) {
            $this->_removeButton('add');
        }
    }

    protected function _prepareLayout() {
        $this->setChild('grid', $this->getLayout()->createBlock($this->_blockGroup.'/'.$this->_controller.'_grid', $this->_controller.'.grid')->setSaveParametersInSession(true));
        return parent::_prepareLayout();
    }
}