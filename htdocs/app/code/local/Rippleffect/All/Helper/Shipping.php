<?php

/**
 * Rippleffect Shipping helper.
 * 
 * Contains various methods for postcode matching in the UK. Hopefully the function names speak for themselves.
 * If you can't grasp what this does by the function names, you shouldn't be programming :)
 * 
 * Most functions will throw an exception if the postcode supplied isn't a valid UK postcode.
 *
 * @category   Rippleffect
 * @package    Rippleffect_All
 * @author     Dan Jones <djones@rippleffect.com>
 */

class Rippleffect_All_Helper_Shipping {
    
    // Hopefully these constants speak for themselves
    const MAINLAND = 1;
    const SCOTTISH_HIGHLANDS = 2;
    const NORTHERN_IRELAND = 4;
    const OFFSHORE_ISLANDS = 8;
    
    /**
     * Return a bitwise integer which flags the various states of the postcode and where it lies
     * within the UK boundary
     * 
     * @param string $postcode
     * @return int
     */
    public function getPostcodeFlags($postcode) {
        $flags = 0;
        if ($this->isMainlandUk($postcode)) {
            $flags += self::MAINLAND;
        }
        if ($this->isScottishHighlands($postcode)) {
            $flags += self::SCOTTISH_HIGHLANDS;
        }
        if ($this->isNorthernIreland($postcode)) {
            $flags += self::NORTHERN_IRELAND;
        }
        if ($this->isOffshoreIslands($postcode)) {
            $flags += self::OFFSHORE_ISLANDS;
        }
        return $flags;
    }
    
    /**
     * Find if supplied postcode is within mainland UK
     * 
     * @param strng $postcode
     * @return bool
     */
    public function isMainlandUk($postcode) {
        if (!$this->isScottishHighlands($postcode) && !$this->isNorthernIreland($postcode) && !$this->isOffshoreIslands($postcode)) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /**
     * Find if supplied postcode is classed within UK Scottish Highlands area
     * 
     * @param string $postcode
     * @return bool
     */
    public function isScottishHighlands($postcode) {
        $range = 
            array(
                "AB10", "AB11", "AB12", "AB13", "AB14", "AB15", "AB16", "AB20", "AB21", "AB22", "AB23", "AB24", "AB25", "AB30", "AB31",
                "AB32", "AB33", "AB34", "AB35", "AB36", "AB37", "AB38", "AB39", "AB41", "AB42", "AB43", "AB44", "AB45", "AB51", "AB52",
                "AB53", "AB54", "AB55", "AB56", "AB99",
                
                "DD8",  "DD9",  "DD10", "DD11", "DD12",
                
                "PA20", "PA21", "PA22", "PA23", "PA25", "PA26", "PA27", "PA28", "PA29", "PA30", "PA31", "PA32", "PA34", "PA35", "PA37",
                
                "PH19", "PH20", "PH21", "PH22", "PH23", "PH24", "PH25", "PH26", "PH30", "PH31", "PH32", "PH33", "PH34", "PH35", "PH36",
                "PH37", "PH38", "PH39", "PH40", "PH41",
                
                "IV1",  "IV2",  "IV3",  "IV4",  "IV5",  "IV6",  "IV7",  "IV8",  "IV9",  "IV10", "IV11", "IV12", "IV13", "IV14", "IV15",
                "IV16", "IV17", "IV18", "IV19", "IV20", "IV21", "IV22", "IV23", "IV24", "IV25", "IV26", "IV27", "IV28", "IV29", "IV30",
                "IV31", "IV32", "IV36", "IV40", "IV41", "IV42", "IV43", "IV44", "IV45", "IV46", "IV47", "IV48", "IV49", "IV51", "IV52",
                "IV53", "IV54", "IV55", "IV56", "IV63",

                "KW1",  "KW2",  "KW3",  "KW4",  "KW5",  "KW6",  "KW7",  "KW8",  "KW9",  "KW10", "KW11", "KW12", "KW13", "KW14", "KW15",
                "KW16", "KW17",
            );
        
        if ($this->isValidUkPostcode($postcode)) {
            if ($this->_isPostcodeInRange($postcode, $range)) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            throw new Exception("Not a valid UK Postcode");
        }
    }
    
    /**
     * Find if supplied postcode is classed within Northern Ireland terrority (BT* postcodes)
     * 
     * @param string $postcode
     * @return bool
     */
    public function isNorthernIreland($postcode) {
        $range =
            array(
                "BT1",  "BT2",  "BT3",  "BT4",  "BT5",  "BT6",  "BT7",  "BT8",  "BT9",  "BT10", "BT11", "BT12", "BT13", "BT14", "BT15",
                "BT16", "BT17", "BT18", "BT19", "BT20", "BT21", "BT22", "BT23", "BT24", "BT25", "BT26", "BT27", "BT28", "BT29", "BT30",
                "BT31", "BT32", "BT33", "BT34", "BT35", "BT36", "BT37", "BT38", "BT39", "BT40", "BT41", "BT42", "BT43", "BT44", "BT45",
                "BT46", "BT47", "BT48", "BT49", "BT51", "BT52", "BT53", "BT54", "BT55", "BT56", "BT57", "BT58", "BT60", "BT61", "BT62",
                "BT63", "BT64", "BT65", "BT66", "BT67", "BT68", "BT69", "BT70", "BT71", "BT74", "BT75", "BT76", "BT77", "BT78", "BT79",
                "BT80", "BT81", "BT82", "BT92", "BT93", "BT94", "BT99",
            );
        
        if ($this->isValidUkPostcode($postcode)) {
            if ($this->_isPostcodeInRange($postcode, $range)) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            throw new Exception("Not a valid UK Postcode");
        }
    }
    
    /**
     * Find is supplied postcode is classed within Offshore Islands territory (Jersey, Guernsey, Ise of Man, etc)
     * Note: DOES NOT INCLUDE NORTHERN IRELAND POSTCODES
     * 
     * @param string $postcode
     * @return bool
     */
    public function isOffshoreIslands($postcode) {
        $range =
            array(
                "HS1",  "HS2",  "HS3",  "HS4",  "HS5",  "HS6",  "HS7",  "HS8",  "HS9",
                
                "KA27", "KA28",
                
                "KW1",  "KW2",  "KW3",  "KW4",  "KW5",  "KW6",  "KW7",  "KW8",  "KW9",  "KW10", "KW11", "KW12", "KW13", "KW14", "KW15",
                "KW16", "KW17",
                
                "PA41", "PA42", "PA43", "PA44", "PA45", "PA46", "PA47", "PA48", "PA49", "PA60", "PA61", "PA62", "PA63", "PA64", "PA65",
                "PA66", "PA67", "PA68", "PA69", "PA70", "PA71", "PA72", "PA73", "PA74", "PA75", "PA76", "PA77", "PA78",
                
                "PH42", "PH43", "PH44",
                
                "ZE1",  "ZE2",  "ZE3",
                
                "IM1",  "IM2",  "IM3",  "IM4",  "IM5",  "IM6",  "IM7",  "IM8",  "IM9",
            );
        
        if ($this->isValidUkPostcode($postcode)) {
            if ($this->_isPostcodeInRange($postcode, $range)) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            throw new Exception("Not a valid UK Postcode");
        }
    }
    
    /**
     * Check if supplied postcode is syntactically valid
     * 
     * @param string $postcode
     * @return bool
     */
    public function isValidUkPostcode(&$postcode) {
        //if (preg_match("/^\s/", $postcode)) {
        if (preg_match("/(^[A-Za-z]{1,2}[0-9]{1,2}[A-Z]?[\s]?[0-9][A-Za-z]{2}$)/", $postcode)) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /**
     * Check if supplied postcode is contained within the supplied range of postcodes.
     * This function is internal to Rippleffect_All_Helper_Shipping class.
     * 
     * @param string $postcode
     * @param array $range
     * @return bool
     */
    private function _isPostcodeInRange($postcode, $range) {
        foreach ($range as $part) {
            if (preg_match("/^" . strtoupper(str_replace(" ", "", $part)). "/i", strtoupper(str_replace(" ", "", $postcode)))) {
                return true;
            }
        }
        return false;
    }
    
}
