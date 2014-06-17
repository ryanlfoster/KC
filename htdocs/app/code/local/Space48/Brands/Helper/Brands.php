<?php
class Space48_Brands_Helper_Brands extends Mage_Core_Helper_Abstract
{
    /**
     * Helper function to return the brand associated against a product.
     *
     * @param $id
     * @return mixed
     */
    public function getBrand($id)
    {
        $product      = Mage::getModel('catalog/product')->load($id);
        $manufacturer = $product->getBrand();

        $name = Mage::getModel('brands/brands')->getCollection()
            ->addFilter('manufacturer_id',$manufacturer)
            ->getData();

        if($name[0]['title']) {
            return $name[0]['title'];
        } else {
            return false;
        }
    }

    /**
     * Returns the link for the brand image
     *
     * @param $brandId
     */
    public function getBrandImage($brandId)
    {
        $product      = Mage::getModel('catalog/product')->load($brandId);
        $manufacturer = $product->getBrand();

        $image = Mage::getModel('brands/brands')->getCollection()
            ->addFilter('manufacturer_id', $manufacturer)
            ->getData();

        if($image[0]['large_logo']) {
            return $image[0]['large_logo'];
        } else {
            return false;
        }
    }

    /**
     * Returns the brand URL page
     *
     * @param $brandId
     */
    public function getBrandUrl($brandId)
    {
        $product      = Mage::getModel('catalog/product')->load($brandId);
        $manufacturer = $product->getBrand();

        $image = Mage::getModel('brands/brands')->getCollection()
            ->addFilter('manufacturer_id', $manufacturer)
            ->getData();

        if($image[0]['url_key']) {
            return $image[0]['url_key'];
        } else {
            return false;
        }
    }

    public function getManufacturerIdByBrandId($brandId)
    {
        $brand = Mage::getModel('brands/brands')
            ->getCollection()
            ->addFieldToFilter('id', $brandId);
        $brandsData = $brand->getData();
        if (is_array($brandsData) && !empty($brandsData)) {
            return $brandsData[0]['manufacturer_id'];
        }
    }
}
