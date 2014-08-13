<?php
class JJM_Styleguide_Block_Adminhtml_Catalog_Product_Edit_Tab
    extends Mage_Adminhtml_Block_Widget
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function canShowTab()
    {
        return true;
    }
    public function getTabLabel()
    {
        return $this->__('Related Products');
    }
    public function getTabTitle()
    {
        return $this->__('Related Products');
    }
    public function isHidden()
    {
        return false;
    }
    public function getTabUrl()
    {
        return $this->getUrl('*/*/custom', array('_current' => true));
    }
    public function getTabClass()
    {
        return 'ajax';
    }
}