<?php
/**
 * Space48 Ltd
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.space48.com/license.html
 *
 * @category   Space48
 * @package    Space48_Custom_Landing_Page
 * @version    0.1.0
 * @copyright  Copyright (c) 2013-2013 Space48 Ltd. (http://www.space48.com)
 * @license    http://www.space48.com/license.html
 * @company    Space48
 * @author     James Cowie (james@space48.com), Matt Edwards (matt@space48.com)
 * @link       http://wiki.space48.com/modules/brands
 */
class Space48_CustomLandingPage_Block_Adminhtml_Brands_Edit extends Mage_Adminhtml_Block_Widget
{
    /**
     * Default controller constructor
     * Loads the edit block and controller, As well as adding new buttons to the edit form.
     */
    public function __construct()
    {
        $this->_objectId   = 'id';
        $this->_blockGroup = 'customlandingpage';
        $this->_controller = 'adminhtml_brands';
        $this->_mode       = 'edit';

        $this->_updateButton('save','label', Mage::helper('customlandingpage')->__('Save Brand'));
        $this->_updateButton('delete','label', Mage::helper('customlandingpage')->__('Delete Brand'));
        
        parent::__construct();
    }
    
    protected function _prepareLayout()
    {
        
        
    }

    /**
     * Creates the header text when editing / adding a new brand.
     * @return string
     */
    public function getHeaderText()
    {
        if(Mage::registry('brands_data') && Mage::registry('brands_data')->getId()) {
            return Mage::helper('customlandingpage')->__("Edit Brand \"%s\" ", $this->htmlEscape(Mage::registry('brands_data')->getTitle()));
        } else {
            return Mage::helper('customlandingpage')->__('Add Brand');
        }
    }

}