<?php
/**
 * Space48 Ltd
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.space48.com/license.html
 *
 * @category   Space48
 * @package    Space48_Custom_Landing_Page
 * @version    0.1.0
 * @copyright  Copyright (c) 2013-2013 Space48 Ltd. (http://www.space48.com)
 * @license    http://www.space48.com/license.html
 * @company    Space48
 * @author     James Cowie (james@space48.com), Matt Edwards (matt@space48.com)
 * @link       http://wiki.space48.com/modules/brands
 */
class Space48_CustomLandingPage_Block_Brands extends Mage_Catalog_Block_Product_List
{
    /**
     * Loads the product collection
     * 
     * @return mixed
     */
    public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }
    
    /**
     * Gets the product collection and manipulates it to only 
     * return products within the selected brand
     * 
     * @return mixed
     */
    protected function _getProductCollection()
    {
        $brand = Mage::registry('brand');
        if (is_null($this->_productCollection))
        {
            $layer = $this->getLayer();
            $this->_productCollection = $layer->getProductCollection();
            
            $this->_productCollection
                    ->getSelect()
                    ->joinInner('catalog_product_flat_1', 'catalog_product_flat_1.entity_id=e.entity_id', 'brand')
                    ->where("catalog_product_flat_1.brand = '".$brand[0]['manufacturer_id']."'");
            //echo $this->_productCollection->getSelect()->__toString();
            
            //$this->_productCollection->addAttributeToFilter('manufacturer',array('eq'=>$brand[0]['manufacturer_id']));
            //echo $this->_productCollection->getSelect();          
            $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());
        }
        return $this->_productCollection;
    }

    /**
     * Load the brands registered in the controller index action
     *
     * @return mixed
     */
    public function getBrands()
    {
        $brands = Mage::registry('brands');
        return $brands;
    }

    /**
     * Load the brand data registered in the controller index action
     *
     * @return mixed
     */
    public function getBrandPage()
    {
        $brand = Mage::registry('brand');
        return $brand;
    }
}