<?php

class KidsCavern_StockUpdate_Model_Updater {

    const APC_MAP_KEY = "kidscavern_product_sku_id_map";

    protected function _getSkuMap() {
        $map = array();
        
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        
        $query = 'SELECT entity_id,sku FROM ' . $resource->getTableName('catalog/product');
        $results = $readConnection->fetchAll($query);
        
        foreach ($results as $row) {
            $map[$row['sku']] = $row['entity_id'];
        }
        
        return $map;
    }

    public function run() {
        ini_set("memory_limit", "512M");
        ini_set("max_execution_time", "0");
        set_time_limit(0);

        $this->_log("cron ran at " . date('Y-m-d H:i:s'));

        $filepath = Mage::getBaseDir() . "/var/import/stock.xml";
        
        if(!file_exists($filepath)){
            $this->_log("no xml file found");
            die('no stock file to load');
        }

        $xml = simplexml_load_file($filepath);

        $this->_log("xml file loaded");

        $map = $this->_getSkuMap();

        $this->_log("sku map built");

//        $this->_disableIndexAutoupdate();
        
        $iCount = 0;
        foreach ($xml->sku as $item) {

            $iSku = trim($item->itemnum);

            if (!isset($map[$iSku])) {
                //$this->_log("product ".$iSku." not found");
                continue;
            }

            $this->_log("product " . $iSku . " found");

            $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($map[$iSku]);
            /* @var $stockItem Mage_CatalogInventory_Model_Stock_Item */

            $qty = trim($item->qty);

            $this->_log("product " . $iSku . " new qty = " . $qty);

            $stockItem->setQty($qty);

            //$this->_log("product ".$iSku." new qty = ".$qty." set");

            $stockItem->setIsInStock(($qty > 0 ? true : false));

            if ($qty > 0) {
                $this->_log("product " . $iSku . " is in stock. Field set to true");
            }
            else {
                $this->_log("product " . $iSku . " is out of stock. Field set to false");
            }


            $stockItem->save();

            $this->_log("product " . $iSku . " saved");

            unset($stockItem);

            $iCount++;
        }

//        try {
//            $this->_enableIndexAutoupdate();
//        }
//        catch (Exception $e) {
//            $this->_log("Exception thrown during re-indexing: " . $e->getMessage());
//        }

        $this->_log($iCount . " number of products found and updated");
    }

    protected function _log($msg) {
        Mage::log($msg, null, "stockupdate.log");
    }

    protected function _disableIndexAutoupdate() {
        $this->_log("Disabling auto index on stock");
        Mage::getSingleton('index/indexer')
                ->getProcessByCode("cataloginventory_stock")
                ->setMode(Mage_Index_Model_Process::MODE_MANUAL)
                ->save();
        
        $this->_log("Disabling auto index on product attributes");
        Mage::getSingleton('index/indexer')
                ->getProcessByCode("catalog_product_attribute")
                ->setMode(Mage_Index_Model_Process::MODE_MANUAL)
                ->save();
        
        $this->_log("Disabling auto index on product prices");
        Mage::getSingleton('index/indexer')
                ->getProcessByCode("catalog_product_price")
                ->setMode(Mage_Index_Model_Process::MODE_MANUAL)
                ->save();

        return $this;
    }

    protected function _enableIndexAutoupdate() {
        $this->_log("Enabling auto index and re-indexing stock");
        Mage::getSingleton('index/indexer')
                ->getProcessByCode("cataloginventory_stock")
                ->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)
                ->save()
                ->reindexAll();
        
        $this->_log("Enabling auto index and re-indexing product attributes");
        Mage::getSingleton('index/indexer')
                ->getProcessByCode("catalog_product_attribute")
                ->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)
                ->save()
                ->reindexAll();
        
        $this->_log("Enabling auto index and re-indexing product prices");
        Mage::getSingleton('index/indexer')
                ->getProcessByCode("catalog_product_price")
                ->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)
                ->save()
                ->reindexAll();
        
        $this->_log("Fin.");

        return $this;
    }

}
