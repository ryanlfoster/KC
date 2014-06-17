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
 * @copyright  Copyright (c) 2013-2013 Space48 Ltd. (http://www.space48.com)
 * @license    http://www.space48.com/license.html
 * @version    0.1.0
 * @company    Space48
 * @author     James Cowie (james@space48.com), Matt Edwards (matt@space48.com)
 * @link       http://wiki.space48.com/modules/brands
 */
class Space48_CustomLandingPage_Helper_Brands extends Mage_Core_Helper_Abstract
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
        $manufacturer = $product->getManufacturer();

        $name = Mage::getModel('customlandingpage/brands')->getCollection()
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
        $manufacturer = $product->getManufacturer();

        $image = Mage::getModel('customlandingpage/brands')->getCollection()
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
        $manufacturer = $product->getManufacturer();

        $image = Mage::getModel('customlandingpage/brands')->getCollection()
            ->addFilter('manufacturer_id', $manufacturer)
            ->getData();

        if($image[0]['url_key']) {
            return $image[0]['url_key'];
        } else {
            return false;
        }
    }

}