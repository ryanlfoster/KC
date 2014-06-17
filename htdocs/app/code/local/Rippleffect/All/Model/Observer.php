<?php
/**
 * Rippleffect System Observer
 *
 * @category   Rippleffect
 * @package    Rippleffect_All
 * @author     Dan Jones <djones@rippleffect.com>
 */
class Rippleffect_All_Model_Observer {

    /**
     * Detect Useragent of visitor, flag if is a mobile device and redirect accordingly
     *
     * @param Varien_Event_Observer $observer
     * @TODO
     */
    public function detectUa($observer) {
        if (Mage::getStoreConfig("rippleffect/mobile/redirect_on_mobile_useragent") == 0) {
            return true;
        }

        return true;
        
        $event = $observer->getEvent();
        $controller = $event->getControllerAction();
        $request = $controller->getRequest();

        $mobile = false;

        $mobileUserAgents = explode(",", Mage::getStoreConfig("rippleffect/mobile/mobile_useragents"));

        foreach ($mobileUserAgents as $ua) {
            if (preg_match("/" . $ua . "/i", $_SERVER['HTTP_USER_AGENT'])) {
                $mobile = true;
            }
        }

        $redirectToMobile = Mage::getStoreConfig("rippleffect/mobile/redirect_to_mobile");
        $redirectToNormal = Mage::getStoreConfig("rippleffect/mobile/redirect_to_normal");

        if (substr($redirectToMobile, 0, 4) == "http") {
            $host = substr($redirectToMobile, strpos($redirectToMobile, "://") + 2);
            if ($mobile && $_SERVER['HTTP_HOST'] != $host) {
                header("Location:" . $redirectToMobile);
                exit;
            }
            else if (!$mobile && $_SERVER['HTTP_HOST'] == $host) {
                header("Location:" . $redirectToNormal);
                exit;
            }
        }


    }

    /**
     * Function to override default adminhtml theme with Rippleffect's customisations
     */
    public function overrideAdminTheme() {
        Mage::getDesign()->setArea('adminhtml')->setTheme("rippleffect");
    }
    
    // called by "catalog_controller_product_init"
    public function setCurrentCategory(Varien_Event_Observer $observer) {
        $_product = $observer->getProduct();
        
        // only interfere if current_category isn't already defined. this preserves magento setting current_category
        // to one higher up the chain (i.e; a top level category as opposed to a sub category)
        if (is_null(Mage::registry('current_category'))) {
            $_productCategories = $_product->getCategoryIds();
            if (is_array($_productCategories) && isset($_productCategories[0])) {
                $_category = Mage::getModel('catalog/category')->load($_productCategories[0]);
                Mage::register('current_category', $_category);
            }
        }
    }
    
    // clear featured products cache (if any)
    public function clearFeaturedProductsCache(Varien_Event_Observer $observer) {
        $cache = Mage::app()->getCache();
        
        $stores = Mage::app()->getStores();
        foreach ($stores as $store) {
            $storeId = $store->getId();
            $cache->remove("FEATURED_PRODUCTS_COLLECTION_" . $storeId);
        }
    }

}
