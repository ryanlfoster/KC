<?php

class Rippleffect_Shipping_Adminhtml_MethodsController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('sales/shipping_methods')
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Shipping Methods'), $this->__('Shipping Methods'));
        return $this;
    }

    public function indexAction() {
        $this->_title($this->__('Sales'))->_title($this->__('Shipping Methods'));

        $this->_initAction()
             ->_addContent($this->getLayout()->createBlock('rippleffect_shipping/adminhtml_methods'))
             ->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('rippleffect_shipping/method')->load($id);
        Mage::register('rippleffect_shipping_method_data', $model);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            $this->loadLayout();
            $this->_setActiveMenu('sales/rippleffect_shipping');

            $this->_addContent($this->getLayout()->createBlock('rippleffect_shipping/adminhtml_methods_edit'));
            $this->renderLayout();
            
        }
        else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rippleffect_shipping')->__("wtf you talkin' about foo?"));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            // don't quite know how to use this...
            unset($data['form_key']);

            try {
                if (!is_numeric($data['_charge'])) {
                    throw new Exception("Charge value is not a number.");
                }
                if ($data['_canHaveFree'] == 1) {
                    if ($data['_freeBand'] == "") {
                        throw new Exception("Please enter a cart value for which free shipping will be applied after.");
                    }
                    if (!is_numeric($data['_freeBand'])) {
                        throw new Exception("Free shipping band value is not a number.");
                    }
                }
                if ($data['_applyAllCountries'] != 1) {
                    if (count($data['_applyCountries']) == 0 || (count($data['_applyCountries']) == 1 && $data['_applyCountries'][0] == "")) {
                        throw new Exception("Please select some countries to apply shipping rate to.");
                    }
                    
                    // clean up a little..
                    if (count($data['_applyCountries']) > 1) {
                        foreach ($data['_applyCountries'] as $k => $country) {
                            if ($country == "") unset($data['_applyCountries'][$k]);
                        }
                    }
                }
                if ($data['_applyDayRestriction'] != Rippleffect_Shipping_Helper_Data::ALL_OPTIONS) {
                    if (count($data['_applyDays']) == 0) {
                        throw new Exception("Please select some days to apply (or except) from the rule.");
                    }
                }
                
                $method = Mage::getModel('rippleffect_shipping/method');
                if (!is_null($this->getRequest()->getParam('id'))) {
                    $method->load($this->getRequest()->getParam('id'));
                }

                foreach ($data as $key => $value) {
                    if (strpos($key, "_") === 0) {
                        $method->setExtraData($key, $value);
                    }
                    else {
                        $method->setData($key, $value);
                    }
                }

                $method->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('rippleffect_shipping')->__('Shipping Method was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                
                return;
            }
        }
        else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('banners')->__('Unable to save'));
            $this->_redirect('*/*/');
        }
    }

}
