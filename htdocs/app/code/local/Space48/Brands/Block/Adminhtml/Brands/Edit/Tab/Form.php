<?php
class Space48_Brands_Block_Adminhtml_Brands_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('form_form',
            array('legend'=> 'Brands'));

        $man = $this->getAllManufacturers();

        $fieldset->addField('manufacturer_id','select',array(
            'label'    => Mage::helper('brands')->__('Brand'),
            'class'    => 'required-entity',
            'required' => true,
            'values'   => $man,
            'name'     => 'manufacturer_id',
        ));

        $fieldset->addField('title', 'text', array(
            'label'    => Mage::helper('brands')->__('Brand Name'),
            //'class'    => 'required-entry',
            'required' => true,
            'name'     => 'title'
        ));

        $fieldset->addField('small_logo', 'image', array(
            'value'    => 'http://someuel.com/image.png',
            'label'    =>  Mage::helper('brands')->__('Logo'),
            'name'     => 'small_logo',
        ));

        $fieldset->addField('large_logo', 'image', array(
            'value'    => 'http://someuel.com/image.png',
            'label'    => Mage::helper('brands')->__('Banner'),
            'name'     => 'large_logo',
        ));

        $fieldset->addField('description','editor', array(
            'label'    => Mage::helper('brands')->__('Description'),
            //'class'    => 'required-entity',
            //'required' => true,
            'config'   => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'wysiwyg'  => true,
            'name'     => 'description',
        ));

        $fieldset->addField('meta_title', 'text', array(
            'label'    => Mage::helper('brands')->__('Meta Title'),
            //'class'    => 'required-entity',
            //'required' => true,
            'name'     => 'meta_title',
        ));

        $fieldset->addField('meta_keywords', 'textarea', array(
            'label'    => Mage::helper('brands')->__('Meta Keywords'),
            //'class'    => 'required-entity',
            //'required' => true,
            'name'     => 'meta_keywords',
        ));

        $fieldset->addField('meta_description', 'textarea', array(
            'label'    => Mage::helper('brands')->__('Meta Description'),
            //'class'    => 'required-entity',
            //'required' => true,
            'name'     => 'meta_description'
        ));

        $fieldset->addField('status','select',array(
            'label'    => Mage::helper('brands')->__('Status'),
            'class'    => 'required-entity',
            'required' => true,
            'values'   => array('-1'=>'Please Select..','1' => 'Enabled','2' => 'Disabled'),
            'name'     => 'status',
        ));

        $fieldset->addField('url_key', 'text', array(
            'label'    => Mage::helper('brands')->__('URL Key'),
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

    protected function getAllManufacturers()
    {
        $product = Mage::getModel('catalog/product');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($product->getResource()->getTypeId())
            ->addFieldToFilter('attribute_code', 'brand');
        $attribute = $attributes->getFirstItem()->setEntity($product->getResource());
        $manufacturers = $attribute->getSource()->getAllOptions(false);
        return $manufacturers;
    }

}