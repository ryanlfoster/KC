<?php

class Space48_CustomLandingPage_Block_Adminhtml_Brands_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function _construct() {
        $this->setId('brands_tabs');
        $this->setDestElementId('content');
        $this->setTitle(Mage::helper('customlandingpage')->__('Brand panding page'));
        
        parent::_construct();
    }
    
    protected function _beforeToHtml() {
        
        $this->addTab('form_section', array(
            'label' => Mage::helper('customlandingpage')->__('General information'),
            'title' => Mage::helper('customlandingpage')->__('General information'),
            'content' => $this->getLayout()->createBlock('customlandingpage/adminhtml_brands_edit_tabs_form')->toHtml(),
            //'content' => 'GENERAL INFO',
        ));

        /*$this->addTab('products_section', array(
            'label'     => Mage::helper('customlandingpage')->__('Products'),
            'title' => Mage::helper('customlandingpage')->__('Products'),
            'content'   => $this->getLayout()->createBlock('customlandingpage/adminhtml_brands_edit_tabs_products')->toHtml(),
        ));*/

        return parent::_beforeToHtml();
    }
}