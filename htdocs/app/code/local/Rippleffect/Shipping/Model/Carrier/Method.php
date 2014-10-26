<?php

class Rippleffect_Shipping_Model_Carrier_Method extends Mage_Shipping_Model_Carrier_Abstract {
    protected $_code = 'rippleffect_shipping';
    private $_reallyHighNumber = 9999999;

    public function collectRates(Mage_Shipping_Model_Rate_Request $request) {
        if (!Mage::getStoreConfig('carriers/'.$this->_code.'/active')) {
            return false;
        }

        $items = $request->getAllItems();
        $cartCategories = array();
        foreach ($items as $item) {
            $parentIds =
                Mage::getResourceSingleton('catalog/product_type_configurable')
                    ->getParentIdsByChild($item->getProduct()->getId());
            if (isset($parentIds[0])) {
                $product = Mage::getModel('catalog/product')->load($parentIds[0]);
            }
            else {
                $product = $item->getProduct();
            }
            
            foreach ($product->getCategoryIds() as $id) {
                if (!in_array($id, $cartCategories)) {
                    array_push($cartCategories, $id);
                }
            }

            if (Mage::helper('tax')->priceIncludesTax()) {
                $request->setPackageValue($request->getPackageValue() + $item->getTaxAmount());
            }
        }

        $methods = Mage::getModel('rippleffect_shipping/method')->getCollection();
        $result = Mage::getModel('shipping/rate_result');

        $finalMethods = array();
        $cheapestPrice = $this->_reallyHighNumber;
        foreach ($methods as $method) {
            $method = $method->load($method->getId());
            //Mage::log("method: " . $method->getTitle());
            if ($method->getActive() != 1) {
                //Mage::log("not active.");
                continue;
            }

            $canUse = true;
            switch ($method->getExtraData("category")) {
                case Rippleffect_Shipping_Helper_Data::ALL_EXCEPT_SELECTED_OPTIONS: { // allexcept
                    $exclusions = $method->getExtraData("categories");
                    
                    //Mage::log("all except categories (" . implode(",", $exclusions) . ") (cart categories: " . implode(",", $cartCategories) . ")");
                    
                    $common = array_intersect($cartCategories, $exclusions);
                    $diff = array_diff($cartCategories, $exclusions);
                    
                    //Mage::log("common: " . implode(",", $common) . ", different: " . implode(",", $diff));

                    $canUse = true;
                    if (count($common) > 0) {
                        $canUse = false;
                    }
                    if (count($diff) > 0) {
                        $canUse = true;
                    }
                } break;
                case Rippleffect_Shipping_Helper_Data::ONLY_SELECTED_OPTIONS: { //onlyselected
                    //Mage::log("only selected categories (" . implode(",", $method->getExtraData("categories")) . ") (cart categories: " . implode(",", $cartCategories) . ")");
                    $inclusions = $method->getExtraData("categories");
                    $canUse = false;
                    foreach ($method->getExtraData("categories") as $catId) {
                        if (in_array($catId, $cartCategories)) {
                            $canUse = true;
                        }
                    }
                } break;
                case Rippleffect_Shipping_Helper_Data::ALL_OPTIONS: // all
                default: {
                    //Mage::log("all categories");
                    $canUse = true;
                }
            }
            //Mage::log("can use 1: " . var_export($canUse, true));
            if (!$canUse) {
                continue;
            }
            
            if ($canUse) {
                // do country checking here
                // set $canUse to false to prevent adding method to result set
                if ($method->getExtraData('applyAllCountries') != 1) {
                    if (!in_array($request->getDestCountryId(), $method->getExtraData('applyCountries'))) {
                        $canUse = false;
                    }
                }
            }
            
            //Mage::log("can use 2: " . var_export($canUse, true));
            if (!$canUse) {
                continue;
            }
            
            if ($canUse && $method->getExtraData('applyAllCountries') != 1) {
                if (!in_array($request->getDestCountryId(), $method->getExtraData('applyCountries'))) {
                    $canUse = false;
                }
            }
            
            //Mage::log("can use 3: " . var_export($canUse, true));
            if (!$canUse) {
                continue;
            }
            
            if ($canUse && $request->getDestCountryId() == "GB") {
                // check here if we can do conditional shipping on mainland/non-mainland deliveries

                if (count($method->getExtraData('britishAreas')) == 1 && $method->getExtraData('britishAreas', 0) == Rippleffect_Shipping_Helper_Data::ALL_AREAS)
                    $canUse = true; // so i don't have to invert the above if statement. that's so ugly and last century..
                else {
                    $flags = Mage::helper('rippleffect/shipping')->getPostcodeFlags($request->getDestPostcode());

                    if ($flags & Rippleffect_All_Helper_Shipping::MAINLAND) {
                        if (!in_array(Rippleffect_Shipping_Helper_Data::MAINLAND, $method->getExtraData('britishAreas'))) {
                            $canUse = false;
                        }
                    }

                    if ($flags & Rippleffect_All_Helper_Shipping::NORTHERN_IRELAND) {
                        if (!in_array(Rippleffect_Shipping_Helper_Data::NORTHERN_IRELAND, $method->getExtraData('britishAreas'))) {
                            $canUse = false;
                        }
                    }
                    
                    if ($flags & Rippleffect_All_Helper_Shipping::SCOTTISH_HIGHLANDS) {
                        if (!in_array(Rippleffect_Shipping_Helper_Data::SCOTTISH_HIGHLANDS, $method->getExtraData('britishAreas'))) {
                            $canUse = false;
                        }
                    }
                    
                    if ($flags & Rippleffect_All_Helper_Shipping::OFFSHORE_ISLANDS) {
                        if (!in_array(Rippleffect_Shipping_Helper_Data::OFFSHORE_ISLANDS, $method->getExtraData('britishAreas'))) {
                            $canUse = false;
                        }
                    }
                }
            }
            
            //Mage::log("can use 4: " . var_export($canUse, true));
            if (!$canUse) {
                continue;
            }
            
            if ($canUse) {
                // day and time logic
                if ($method->getExtraData('applyDayRestriction') != Rippleffect_Shipping_Helper_Data::ALL_OPTIONS) {
                    $current_day = strtolower(date("D"));
                    
                    switch ($method->getExtraData('applyDayRestriction')) {
                        case Rippleffect_Shipping_Helper_Data::ALL_EXCEPT_SELECTED_OPTIONS: {
                            if (in_array($current_day, $method->getExtraData('applyDays'))) {
                                $canUse = false;
                            }
                        } break;
                        case Rippleffect_Shipping_Helper_Data::ONLY_SELECTED_OPTIONS: {
                            if (!in_array($current_day, $method->getExtraData('applyDays'))) {
                                $canUse = false;
                            }
                        } break;
                    }
                }
            }
            
            //Mage::log("can use 5: " . var_export($canUse, true));
            if (!$canUse) {
                continue;
            }
            
            if ($canUse) {
                if ($method->getExtraData('useTimeRestriction')) {
                    date_default_timezone_set(Mage::getStoreConfig('general/locale/timezone'));
                    
                    $current_time = date("Hi"); // no, i'm not saying hello... you twerp!
                    
                    if ($method->getExtraData('timeRestrictionApply') == "before") {
                        if ($current_time >= $method->getExtraData('timeRestriction')) {
                            $canUse = false;
                        }
                    }
                    else if ($method->getExtraData('timeRestrictionApply') == "after") {
                        if ($current_time < $method->getExtraData('timeRestriction')) {
                            $canUse = false;
                        }
                    }
                }
            }
            
            //Mage::log("can use 6: " . var_export($canUse, true));
            if (!$canUse) {
                continue;
            }
            
            
            if ($canUse) {
                $finalMethod =
                    Mage::getModel('shipping/rate_result_method')
                        ->setCarrier($this->_code)
                        //->setCarrierTitle($method->getExtraData("name"))
                        ->setMethod($this->_code . "_" . $method->getId())
                        ->setMethodTitle($method->getExtraData("name"))
                        ->setCost($method->getExtraData("charge"))
                        ->setPrice($method->getExtraData("charge"))
                        ->setOriginalMethod($method)
                    ;

                if ($method->getExtraData("canHaveFree") && $request->getPackageValue() > $method->getExtraData("freeBand")) {
                    $finalMethod->setCost(0)->setPrice(0);
                }

                $shippingType = $method->getExtraData('shippingType');
                
                //Mage::log("method title: " . $method->getTitle());
                //Mage::log("method name: " . $method->getExtraData('name'));
                //Mage::log("method type: " . $shippingType);
                //Mage::log("price: " . $finalMethod->getCost());
                
                
                if (!isset($finalMethods[$shippingType])) {
                    //Mage::log("adding first " . $shippingType . " to finalMethods");
                    $finalMethods[$shippingType] = $finalMethod;
                }
                else if (isset($finalMethods[$shippingType])) {
                    //Mage::log("method for " . $shippingType . " already set. checking if we can override or not");
                    if ($finalMethods[$shippingType]->getOriginalMethod()->getExtraData('overridesAll') != 1) {
                        //Mage::log("that would be a yes");
                        $finalMethods[$shippingType] = $finalMethod;
                    }
                    else {
                        //Mage::log("that would be negative!");
                    }
                }
            }
            
            //Mage::log("can use 7: " . var_export($canUse, true));
            if (!$canUse) {
                continue;
            }
        }
        
        foreach ($finalMethods as $type => $method) {
            $oMethod = $method->getOriginalMethod();
            if (is_numeric(substr($oMethod->getExtraData("charge"), 0, 1)) && $method->getCost() < $cheapestPrice) {
                if ($oMethod->getExtraData('dontIncludeIfOthers') == "1" && count($finalMethods) > 1) {
                    $cheapestPrice = $method->getCost();
                }
            }
        }
        
        if ($cheapestPrice == $this->_reallyHighNumber) { // nothing set cheapest price above, so assume zero.
            $cheapestPrice = 0;
        }
        
        // go about cycling through the methods we collected above and add them into the rate_result set
        foreach ($finalMethods as $type => $method) {
            $oMethod = $method->getOriginalMethod();
            
            //Mage::log($type . " = " . $oMethod->getTitle());
            //Mage::log("include with others: " . $oMethod->getExtraData('dontIncludeIfOthers'));
            //Mage::log("price: " . $method->getCost());
            
            if (($oMethod->getExtraData('dontIncludeIfOthers') == "0" && count($finalMethods) == 1) || $oMethod->getExtraData('dontIncludeIfOthers') == "1") {
                $modifier = null;
                $charge = $oMethod->getExtraData("charge");
                if (!is_numeric(substr($charge, 0, 1))) {
                    $modifier = substr($charge, 0, 1);
                    switch ($modifier) {
                        case "+": {
                            $charge = $cheapestPrice + substr($charge, 1);
                        } break;
                        case "-": {
                            $charge = $cheapestPrice - substr($charge, 1);
                        } break;
                        default: {
                            // dunno wtf you're talking about. strip the modifier out and just use the value, so at least we're charging *something*
                            $charge = substr($charge, 1);
                        }
                    }
                    //Mage::log("adjusting charge to: " . $charge);
                    $method->setCost($charge)->setPrice($charge);
                }
                $result->append($method);
            }
        }

        return $result;
    }
}
