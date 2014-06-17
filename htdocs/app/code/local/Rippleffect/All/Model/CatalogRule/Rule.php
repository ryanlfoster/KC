<?php

/**
 * CatalogRule_Rule override
 *
 * @category   Rippleffect
 * @package    Rippleffect_All
 * @author     Dan Jones <djones@rippleffect.com>
 */
class Rippleffect_All_Model_CatalogRule_Rule extends Mage_CatalogRule_Model_Rule {

    /**
     * Refresh product block HTML and indexes when product is saved. Check config
     * value from Admin since this can be turned off if it affects performance, and
     * allow the cronjob to take over on a daily basis.
     * 
     * @param int|Mage_Catalog_Model_Product $product
     */
    public function applyAllRulesToProduct($product) {
        if (Mage::getStoreConfig('rippleffect/settings/auto_refresh_invalid_cache') == 1) {
            $this->_getResource()->applyAllRulesForDateRange(NULL, NULL, $product);
            $this->_invalidateCache();

            //Notice this little line
            Mage::app()->getCacheInstance()->cleanType('block_html');

            $indexProcess = Mage::getSingleton('index/indexer')->getProcessByCode('catalog_product_price');
            if ($indexProcess) {
                $indexProcess->reindexAll();
            }
        }
        else {
            parent::applyAllRulesToProduct($product);
        }
    }

}
