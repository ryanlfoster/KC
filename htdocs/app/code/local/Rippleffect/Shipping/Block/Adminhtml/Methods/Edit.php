<?php

class Rippleffect_Shipping_Block_Adminhtml_Methods_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'rippleffect_shipping';
        $this->_controller = 'adminhtml_methods';

        $this->_updateButton('save', 'label', Mage::helper('rippleffect_shipping')->__('Save'));
        $this->removeButton('delete');

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
Array.prototype.has=function(v){for (i=0;i<this.length;i++){if (this[i]==v) return i;}return false;}
var _categoriesTR = $('_categories').up('tr');
var _britishAreasTR = $('_britishAreas').up('tr');
var _applyDaysTR = $('_applyDays').up('tr');
var _timeRestrictionApplyTR = $('_timeRestrictionApply').up('tr');
var _timeRestrictionTR = $('_timeRestriction').up('tr');

function saveAndContinueEdit(){ editForm.submit($('edit_form').action+'back/edit/'); }
Event.observe('_category', 'change', function() { if (this.getValue() != 'all') { _categoriesTR.show(); } else { _categoriesTR.hide(); } });
Event.observe('_applyCountries', 'change', function() { if (this.getValue().has('GB') !== false) { _britishAreasTR.show(); } else { _britishAreasTR.hide(); } });
Event.observe('_applyDayRestriction', 'change', function() { if (this.getValue() != 'all') { _applyDaysTR.show(); } else { _applyDaysTR.hide(); } });
Event.observe('_useTimeRestriction', 'change', function() { if (this.getValue() == 1) { _timeRestrictionApplyTR.show(); _timeRestrictionTR.show(); } else { _timeRestrictionApplyTR.hide(); _timeRestrictionTR.hide(); } });

if ($('_category').getValue() != 'all') { _categoriesTR.show(); } else { _categoriesTR.hide(); }
if ($('_applyCountries').getValue().has('GB') !== false) { _britishAreasTR.show(); } else { _britishAreasTR.hide(); }
if ($('_applyDayRestriction').getValue() != 'all') { _applyDaysTR.show(); } else { _applyDaysTR.hide(); }
if ($('_useTimeRestriction').getValue() == 1) { _timeRestrictionApplyTR.show(); _timeRestrictionTR.show(); } else { _timeRestrictionApplyTR.hide(); _timeRestrictionTR.hide(); }
        ";
    }

    public function getHeaderText() {
        if( Mage::registry('rippleffect_shipping_method_data') && Mage::registry('rippleffect_shipping_method_data')->getId() ) {
            return Mage::helper('rippleffect_shipping')->__("Edit Method '%s'", $this->htmlEscape(Mage::registry('rippleffect_shipping_method_data')->getTitle()));
        }
        else {
            return Mage::helper('rippleffect_shipping')->__('Add Method');
        }
    }

}
