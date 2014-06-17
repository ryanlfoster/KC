<?php
class Space48_Brands_Block_Adminhtml_Brands_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId   = 'id';
        $this->_blockGroup = 'brands';
        $this->_controller = 'adminhtml_brands';

    }

    public function getHeaderText()
    {
        // TODO complete later.
    }
}