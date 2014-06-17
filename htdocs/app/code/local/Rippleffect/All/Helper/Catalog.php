<?php

/**
 * Rippleffect Catalog helper
 *
 * @category   Rippleffect
 * @package    Rippleffect_All
 * @author     Dan Jones <djones@rippleffect.com>
 */

class Rippleffect_All_Helper_Catalog extends Mage_Core_Helper_Abstract {

    /**
     * Get a list of sub-categories of a given parent category
     *
     * @param mixed $parentId Can be Mage_Catalog_Model_Category or Category ID
     * @param bool $sorted Not used yet
     * @param bool $asCollection True to return as Varien_Data_Collection, false to return as array
     * @param bool $toLoad Not used yet
     * @return mixed
     */
    public function getSubCategories($parentId, $sorted=false, $asCollection=false, $toLoad=true) {
        if ($asCollection) {
            $rtn = new Varien_Data_Collection();
        }
        else {
            $rtn = array();
        }
        
        $category = Mage::getModel('catalog/category');
        /* @var $category Mage_Catalog_Model_Category */
        if (!$category->checkId($parentId)) {
            return $rtn;
        }

        $categories =
            $category
                ->getCollection()
                ->addAttributeToFilter("parent_id", $parentId)
                ->addAttributeToFilter("include_in_menu", 1)
        ;

        foreach ($categories as $category) {
            $category = Mage::getModel('catalog/category')->load($category->getId());
            if ($asCollection) {
                $rtn->addItem($category);
            }
            else {
                array_push($rtn, $category);
            }
        }

        return $rtn;
    }

}