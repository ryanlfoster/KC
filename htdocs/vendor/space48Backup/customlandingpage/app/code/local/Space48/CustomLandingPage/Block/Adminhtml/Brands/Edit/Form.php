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
class Space48_CustomLandingPage_Block_Adminhtml_Brands_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'action'  => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'  => 'post',
            'enctype' => 'multipart/form-data',
        ));

        $form->setUseContainer(true);

        $this->setForm($form);

        $fieldset = $form->addFieldset('edit_form',array('legend' => Mage::helper('customlandingpage')->__('Brand Information')));

        $man = $this->getAllManufacturers();

        $fieldset->addField('manufacturer_id','select',array(
            'label'    => Mage::helper('customlandingpage')->__('Brand'),
            'class'    => 'required-entity',
            'required' => true,
            'values'   => $man,
            'name'     => 'manufacturer_id',
        ));

        $fieldset->addField('title', 'text', array(
            'label'    => Mage::helper('customlandingpage')->__('Brand Name'),
            //'class'    => 'required-entry',
            'required' => true,
            'name'     => 'title'
        ));

        /*$fieldset->addField('small_logo', 'image', array(
            'value'    => 'http://someurl.com/image.png',
            'label'    => Mage::helper('splash')->__('Small Logo'),
            'name'     => 'small_logo',
        ));*/

        $fieldset->addField('large_logo', 'image', array(
            'value'    => 'http://someuel.com/image.png',
            'label'    => Mage::helper('customlandingpage')->__('Logo'),
            'name'     => 'large_logo',
        ));

        $fieldset->addField('description','editor', array(
            'label'    => Mage::helper('customlandingpage')->__('Description'),
            //'class'    => 'required-entity',
            //'required' => true,
            'config'   => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'wysiwyg'  => true,
            'name'     => 'description',
        ));

        $fieldset->addField('meta_title', 'text', array(
            'label'    => Mage::helper('customlandingpage')->__('Meta Title'),
            //'class'    => 'required-entity',
            //'required' => true,
            'name'     => 'meta_title',
        ));

        $fieldset->addField('meta_keywords', 'textarea', array(
            'label'    => Mage::helper('customlandingpage')->__('Meta Keywords'),
            //'class'    => 'required-entity',
            //'required' => true,
            'name'     => 'meta_keywords',
        ));

        $fieldset->addField('meta_description', 'textarea', array(
            'label'    => Mage::helper('customlandingpage')->__('Meta Description'),
            //'class'    => 'required-entity',
            //'required' => true,
            'name'     => 'meta_description'
        ));

        $fieldset->addField('status','select',array(
            'label'    => Mage::helper('customlandingpage')->__('Status'),
            'class'    => 'required-entity',
            'required' => true,
            'values'   => array('-1'=>'Please Select..','1' => 'Enabled','2' => 'Disabled'),
            'name'     => 'status',
        ));

        $fieldset->addField('url_key', 'text', array(
            'label'    => Mage::helper('customlandingpage')->__('URL Key'),
            'class'    => 'required-entity',
            'required' => true,
            'name'     => 'url_key',
        ));


        if(Mage::getSingleton('adminhtml/session')->getBrandsData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBrandsData());
            Mage::getSingleton('adminhtml/session')->setBrandsData(null);
        } elseif (Mage::registry('brands_data')) {
            $form->setValues(Mage::registry('brands_data')->getData());
        }

        return parent::_prepareForm();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled())
        {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    protected function getAllManufacturers()
    {
        $product = Mage::getModel('catalog/product');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($product->getResource()->getTypeId())
            ->addFieldToFilter('attribute_code', 'manufacturer');
        $attribute = $attributes->getFirstItem()->setEntity($product->getResource());
        $manufacturers = $attribute->getSource()->getAllOptions(false);
        return $manufacturers;
    }
}