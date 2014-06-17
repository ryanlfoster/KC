<?php
class Space48_Brands_Block_Adminhtml_Brands_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Main class constructor
     *  Sets the element ID for the tabs and assigns the element ID for content to be
     *  injected
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('form_tabs');
        $this->setDestElementId('edit_form'); // this should be same as the form id define above
        $this->setTitle('Manage Brands');
    }

    /**
     * Magento method to add JS Tabs to the admin area
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => 'General',
            'title'     => 'General',
            'content'   => $this->getLayout()->createBlock('brands/adminhtml_brands_edit_tab_form')->toHtml(),
        ));

        $this->addTab('product_section', array(
            'label'     => 'Products',
            'title'     => 'Products',
            'content'   => $this->getLayout()->createBlock('brands/adminhtml_brands_edit_tab_products')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}