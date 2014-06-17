<?php

class Rippleffect_Shipping_Model_Method extends Mage_Core_Model_Abstract {

    protected $_extraData = array();

    public function _construct() {
        parent::_construct();
        $this->_init('rippleffect_shipping/method');
    }

    public function getData($key='', $index=null) {
        if ($key == "") {
            $this->setData("conditions", serialize($this->getExtraData()));
        }

        $data = parent::getData($key, $index);
        
        return $data;
    }

    public function setData($key, $value = null) {
        if (!is_array($key) && substr($key, 0, 1) == "_") {
            $this->setExtraData($key);
        }
        else {
            parent::setData($key, $value);
        }

        return $this;
    }

    public function setExtraData($key, $value) {
        if (substr($key, 0, 1) == "_") {
            $key = substr($key, 1);
        }

        $this->_extraData[$key] = $value;

        return $this;
    }

    public function getExtraData($key = '', $idx = null) {
        if ($key == "") {
            return $this->_extraData;
        }

        if (substr($key, 0, 1) == "_") {
            $key = substr($key, 1);
        }

        if (isset($this->_extraData[$key])) {
            $value = $this->_extraData[$key];
            if (is_array($value) && $idx !== null && is_numeric($idx)) {
                if (isset($value[$idx])) {
                    return $value[$idx];
                }
                else {
                    return null;
                }
            }
            else {
                return $value;
            }
        }
        
        return null;
    }

    public function getExtraDataForForm() {
        $rtn = array();
        if (is_array($this->_extraData)) {
            foreach ($this->_extraData as $key => $value) {
                $rtn['_' . $key] = $value;
            }
        }
        return $rtn;
    }

    protected function  _beforeSave() {
        $this->setData('conditions', serialize($this->_extraData));
        
        parent::_beforeSave();

        return $this;
    }

    protected function  _afterLoad() {
        parent::_afterLoad();

        $this->_extraData = unserialize($this->getData('conditions'));

        return $this;
    }
    
}
