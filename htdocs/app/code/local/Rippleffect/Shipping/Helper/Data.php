<?php

class Rippleffect_Shipping_Helper_Data extends Mage_Core_Helper_Abstract {
    const ALL_OPTIONS = "all";
    const ALL_EXCEPT_SELECTED_OPTIONS = "allexcept";
    const ONLY_SELECTED_OPTIONS = "onlyselected";
    
    const ALL_AREAS = "all";
    const MAINLAND = "mainland";
    const SCOTTISH_HIGHLANDS = "scottish_highlands";
    const NORTHERN_IRELAND = "northern_ireland";
    const OFFSHORE_ISLANDS = "offshore_islands";
    
    public function getShippingTypes() {
        return array(
            array('value' => 'free',     'label' => Mage::helper('rippleffect_shipping')->__("Free")),
            array('value' => 'standard', 'label' => Mage::helper('rippleffect_shipping')->__("Standard Shipping")),
            array('value' => 'next_day', 'label' => Mage::helper('rippleffect_shipping')->__("Next Day")),
            array('value' => 'saturday', 'label' => Mage::helper('rippleffect_shipping')->__("Saturday")),
        );
    }
    
    public function getCategoryOptions() {
        return array(
            array("value" => self::ALL_OPTIONS,                 "label" => $this->__("All Categories")),
            array("value" => self::ALL_EXCEPT_SELECTED_OPTIONS, "label" => $this->__("All Categories Except Selected")),
            array("value" => self::ONLY_SELECTED_OPTIONS,       "label" => $this->__("Only Selected Categories"))
        );
    }
    
    public function getUkDeliveryRestrictedAreas() {
        return array(
            array("value" => self::ALL_AREAS,          "label" => "All Areas (No Restrictions)"),
            array("value" => self::MAINLAND,           "label" => "Mainland"),
            array("value" => self::SCOTTISH_HIGHLANDS, "label" => "Scottish Highlands"),
            array("value" => self::NORTHERN_IRELAND,   "label" => "Northern Ireland"),
            array("value" => self::OFFSHORE_ISLANDS,   "label" => "Offshore Islands"),
        );
    }
    
    public function getDayOptions() {
        return array(
            self::ALL_OPTIONS                 => $this->__("All Days (No Restrictions)"),
            self::ALL_EXCEPT_SELECTED_OPTIONS => $this->__("All Days Except Selected"),
            self::ONLY_SELECTED_OPTIONS       => $this->__("Only Selected Days")
        );
    }
    
    public function getDays() {
        return array(
            array("value" => "mon", "label" => "Monday"),
            array("value" => "tue", "label" => "Tuesday"),
            array("value" => "wed", "label" => "Wednesday"),
            array("value" => "thu", "label" => "Thursday"),
            array("value" => "fri", "label" => "Friday"),
            array("value" => "sat", "label" => "Saturday"),
            array("value" => "sun", "label" => "Sunday"),
        );
    }
    
    public function getTimes() {
        return array(
            array("value" => "0000", "label" => "00:00"),
            array("value" => "0030", "label" => "00:30"),
            array("value" => "0100", "label" => "01:00"),
            array("value" => "0130", "label" => "01:30"),
            array("value" => "0200", "label" => "02:00"),
            array("value" => "0230", "label" => "02:30"),
            array("value" => "0300", "label" => "03:00"),
            array("value" => "0330", "label" => "03:30"),
            array("value" => "0400", "label" => "04:00"),
            array("value" => "0430", "label" => "04:30"),
            array("value" => "0500", "label" => "05:00"),
            array("value" => "0530", "label" => "05:30"),
            array("value" => "0600", "label" => "06:00"),
            array("value" => "0630", "label" => "06:30"),
            array("value" => "0700", "label" => "07:00"),
            array("value" => "0730", "label" => "07:30"),
            array("value" => "0800", "label" => "08:00"),
            array("value" => "0830", "label" => "08:30"),
            array("value" => "0900", "label" => "09:00"),
            array("value" => "0930", "label" => "09:30"),
            array("value" => "1000", "label" => "10:00"),
            array("value" => "1030", "label" => "10:30"),
            array("value" => "1100", "label" => "11:00"),
            array("value" => "1130", "label" => "11:30"),
            array("value" => "1200", "label" => "12:00"),
            array("value" => "1230", "label" => "12:30"),
            array("value" => "1300", "label" => "13:00"),
            array("value" => "1330", "label" => "13:30"),
            array("value" => "1400", "label" => "14:00"),
            array("value" => "1430", "label" => "14:30"),
            array("value" => "1500", "label" => "15:00"),
            array("value" => "1530", "label" => "15:30"),
            array("value" => "1600", "label" => "16:00"),
            array("value" => "1630", "label" => "16:30"),
            array("value" => "1700", "label" => "17:00"),
            array("value" => "1730", "label" => "17:30"),
            array("value" => "1800", "label" => "18:00"),
            array("value" => "1830", "label" => "18:30"),
            array("value" => "1900", "label" => "19:00"),
            array("value" => "1930", "label" => "19:30"),
            array("value" => "2000", "label" => "20:00"),
            array("value" => "2030", "label" => "20:30"),
            array("value" => "2100", "label" => "21:00"),
            array("value" => "2130", "label" => "21:30"),
            array("value" => "2200", "label" => "22:00"),
            array("value" => "2230", "label" => "22:30"),
            array("value" => "2300", "label" => "23:00"),
            array("value" => "2330", "label" => "23:30"),
        );
    }
    
    public function getCategories() {
        $catArray = array(array("value" => "", "label" => "-- Please Select --"));
        
        $categories = Mage::helper('rippleffect')->getCategoryTree();
        foreach ($categories as $category) {
            $level = $category['level'] - 2;
            $label = str_repeat("-", $level * 2) . ($level > 0 ? " " : "") . $category['title'] . "\n";
            array_push($catArray, array("value" => $category['id'], "label" => $label));
        }
        return $catArray;
    }
}
