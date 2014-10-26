<?php

/**
 * Rippleffect Cron
 *
 * @category   Rippleffect
 * @package    Rippleffect_All
 * @author     Dan Jones <djones@rippleffect.com>
 */
class Rippleffect_All_Model_Cron {

    /**
     * Refresh all indexes and caches daily.
     */
    public function dailyIndexCacheRefresh() {
        Mage::log("Refreshing Indexes...");
        $indexProcesses = Mage::getSingleton('index/indexer')->getProcessesCollection();
        foreach ($indexProcesses as $process) {
            $process->reindexAll();
        }
        Mage::log("Indexes refreshed.");

        Mage::log("Refreshing Caches...");
        Mage::app()->getCacheInstance()->clean();
        Mage::log("Caches refreshed.");
    }

}
