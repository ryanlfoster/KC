<?php
class Space48_Brands_Block_Brands extends Mage_Catalog_Block_Product_List
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

    public function getSortedBrands()
    {
        $brands = Mage::registry('brands');

        $letters = array();
        foreach($brands as $brand) {
            //echo $brand['title'] . '<br />';
            $letters[] = substr($brand['title'], 0, 1);
        }

        $letters = array_unique($letters);

        return $letters;
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
