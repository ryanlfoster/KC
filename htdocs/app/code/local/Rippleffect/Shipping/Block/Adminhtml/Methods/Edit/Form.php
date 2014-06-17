<?php

class Rippleffect_Shipping_Block_Adminhtml_Methods_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {
    
    protected function _construct() {
        parent::_construct();
        
    }
    
    protected function _prepareForm() {
        $form =
            new Varien_Data_Form(
                array(
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                    'method' => 'post',
                )
            );

        $fieldset = $form->addFieldset('rippleffect_shipping_method_form', array(
            'legend'    => Mage::helper('rippleffect_shipping')->__('General Information'),
        ));
        
        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('rippleffect_shipping')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
            'note'      => "This is for internal use.",
        ));

        $fieldset->addField('active', 'select', array(
            'label'     => Mage::helper('rippleffect_shipping')->__('Active'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'active',
            'values'    => $this->_getYesNo(),
        ));

        $fieldset->addField('_name', 'text', array(
            'label'     => Mage::helper('rippleffect_shipping')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => '_name',
            'note'      => "This is what will be displayed to the customer in the 'Shipping Methods' section of checkout.",
        ));

        $fieldset->addField('_shippingType', 'select', array(
            'label'     => Mage::helper('rippleffect_shipping')->__('Shipping Type'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => '_shippingType',
            'values'    => Mage::helper('rippleffect_shipping')->getShippingTypes(),
        ));

        $fieldset->addField('_category', 'select', array(
            'label'     => Mage::helper('rippleffect_shipping')->__('Category Selection Type'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => '_category',
            'values'    => Mage::helper('rippleffect_shipping')->getCategoryOptions(),
        ));
        
        $fieldset->addField("_categories", "multiselect", array(
            'label'     => Mage::helper('rippleffect_shipping')->__('Categories to apply to (based on above rule selection)'),
            'required'  => false,
            'name'      => '_categories',
            'values'    => Mage::helper('rippleffect_shipping')->getCategories(),
        ));

        $fieldset->addField('_charge', 'text', array(
            'label'     => Mage::helper('rippleffect_shipping')->__('Charge'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => '_charge',
            'note'      => 'Enter price here in full (i.e; 4.95), or use a modifier to calculate cost of this method based on cheapest method available (i.e; if +10.00 entered, when the cheapest method available is 4.95, charge of 14.95 will be applied to this method)',
        ));

        $fieldset->addField('_canHaveFree', 'select', array(
            'label'     => Mage::helper('rippleffect_shipping')->__('Eligible for Free Shipping?'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => '_canHaveFree',
            'values'    => $this->_getYesNo(),
        ));

        $fieldset->addField('_freeBand', 'text', array(
            'label'     => Mage::helper('rippleffect_shipping')->__('Free Shipping Applied After'),
            'required'  => false,
            'name'      => '_freeBand',
            'note'      => 'Only applies if eligible for free shipping'
        ));

        $fieldset->addField('_overridesAll', 'select', array(
            'label'     => Mage::helper('rippleffect_shipping')->__('Overrides all other shipping methods'),
            'required'  => false,
            'name'      => '_overridesAll',
            'note'      => 'Use this option to force apply this shipping method even if other shipping method rules match the product selection',
            'values'    => $this->_getYesNo(),
        ));

        $fieldset->addField('_dontIncludeIfOthers', 'select', array(
            'label'     => Mage::helper('rippleffect_shipping')->__("Include this method if there are others available?"),
            'required'  => true,
            'name'      => '_dontIncludeIfOthers',
            'note'      => 'This option prevents this method appearing if there are other methods available. Useful to prevent Free Shipping methods appearing when there are paid methods available also.',
            'values'    => $this->_getYesNo(),
        ));
        
        $fieldset->addField('_applyAllCountries', 'select', array(
            'label'     => Mage::helper('rippleffect_shipping')->__("Apply method to all countries?"),
            'name'      => '_applyAllCountries',
            'values'    => $this->_getYesNo(),
        ));
        
        $fieldset->addField('_applyCountries', 'multiselect', array(
            'label'     => Mage::helper('rippleffect_shipping')->__("Apply to specified countries"),
            'required'  => false,
            'name'      => '_applyCountries',
            'values'    => $this->_getCountries(),
        ));
        
        $fieldset->addField("_britishAreas", 'multiselect', array(
            'label'     => Mage::helper('rippleffect_shipping')->__("Apply to these UK Areas"),
            'required'  => false,
            'name'      => '_britishAreas',
            'values'    => Mage::helper('rippleffect_shipping')->getUkDeliveryRestrictedAreas(),
        ));
        
        $fieldset->addField('_applyDayRestriction', 'select', array(
            'label'     => Mage::helper('rippleffect_shipping')->__("Apply method on specific days"),
            'name'      => '_applyDayRestriction',
            'values'    => Mage::helper('rippleffect_shipping')->getDayOptions(),
        ));
        
        $fieldset->addField("_applyDays", 'multiselect', array(
            'label'     => Mage::helper('rippleffect_shipping')->__("Apply above restriction to these days"),
            'required'  => false,
            'name'      => '_applyDays',
            'values'    => Mage::helper('rippleffect_shipping')->getDays(),
        ));
        
        $fieldset->addField('_useTimeRestriction', 'select', array(
            'label'     => Mage::helper('rippleffect_shipping')->__("Use time restriction"),
            'name'      => '_useTimeRestriction',
            'values'    => $this->_getYesNo(),
        ));
        
        $fieldset->addField('_timeRestrictionApply', 'select', array(
            'label'     => Mage::helper('rippleffect_shipping')->__("Apply method:"),
            'name'      => '_timeRestrictionApply',
            'values'    => array(
                array("value" => "before", "label" => "Before"),
                array("value" => "after", "label" => "After"),
            )
        ));
        
        $fieldset->addField('_timeRestriction', 'select', array(
            'label'     => Mage::helper('rippleffect_shipping')->__(""),
            'name'      => '_timeRestriction',
            'values'    => Mage::helper('rippleffect_shipping')->getTimes(),
        ));
        
        $form->setUseContainer(true);
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getFormData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFormData());
            Mage::getSingleton('adminhtml/session')->setFormData(false);
        }
        elseif (Mage::registry('rippleffect_shipping_method_data')) {
            $model = Mage::registry('rippleffect_shipping_method_data');
            $data = array_merge($model->getData(), $model->getExtraDataForForm());
            $form->setValues($data);
        }
        
        return parent::_prepareForm();
    }

    protected function _getYesNo() {
        return array(
            array('value' => '1','label' => Mage::helper('rippleffect_shipping')->__('Yes')),
            array('value' => '0','label' => Mage::helper('rippleffect_shipping')->__('No')),
        );
    }
    
    protected function _getCountries() {
        $model = Mage::getModel('adminhtml/system_config_source_country');
        $values = $model->toOptionArray();
        return $values;
    }
}
