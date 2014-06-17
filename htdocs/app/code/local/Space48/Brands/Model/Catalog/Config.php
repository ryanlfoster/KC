<?php
class Space48_Brands_Model_Catalog_Config extends Mage_Catalog_Model_Config
{
    public function getAttributeUsedForSortByArray()
    {
        // Check if we are on a brands page to show the different position options.
        if (Mage::registry('brand')) {
            $options = array(
                'space48_position' => Mage::helper('catalog')->__('Product Position')
            );
        } else {
            $options = array(
                'position' => Mage::helper('catalog')->__('Position')
            );
        }

        foreach ($this->getAttributesUsedForSortBy() as $attribute) {
            $options[$attribute->getAttributeCode()] = $attribute->getStoreLabel();
        }

        return $options;

    }
}