<?php
class Space48_Brands_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_brands';
        $this->_blockGroup = 'brands';

        $this->_headerText = 'Manage Brands';
        $this->_addButtonLabel = 'Add a brand';

        parent::__construct();
    }
}