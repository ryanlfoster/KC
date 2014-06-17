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
class Space48_CustomLandingPage_Model_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
    
    /*
     * Rewrites the url when changing filters so that it stays as brand name/params
     * 
     * @return mixed
     */
    public function getUrl()
    {
        $query = array(
            $this->getFilter()->getRequestVar()=>$this->getValue(),
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        $brand = Mage::registry('brand');
        $brand_name = $brand[0]['url_key'];
        
        return Mage::getUrl($brand_name, array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));
    }
    
    /*
     * Gets the filter
     * 
     * @return mixed
     */
    public function getFilter()
    {
        $filter = $this->getData('filter');
        if (!is_object($filter)) {
            Mage::throwException(
                Mage::helper('catalog')->__('Filter must be an object. Please set correct filter.')
            );
        }
        return $filter;
    }

    /**
     * Get url for remove item from filter
     *
     * @return string
     */
    public function getRemoveUrl()
    {
        $brand = Mage::registry('brand');
        $brand_name = $brand[0]['url_key'];
        
        $query = array($this->getFilter()->getRequestVar()=>$this->getFilter()->getResetValue());
        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $query;
        $params['_escape']      = true;
        return Mage::getUrl($brand_name, $params);
    }

    /**
     * Get url for "clear" link
     *
     * @return false|string
     */
    public function getClearLinkUrl()
    {
        $brand = Mage::registry('brand');
        $brand_name = $brand[0]['url_key'];
        
        $clearLinkText = $this->getFilter()->getClearLinkText();
        if (!$clearLinkText) {
            return false;
        }

        $urlParams = array(
            '_current' => true,
            '_use_rewrite' => true,
            '_query' => array($this->getFilter()->getRequestVar() => null),
            '_escape' => true,
        );
        return Mage::getUrl($brand_name, $urlParams);
    }

    /**
     * Get item filter name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getFilter()->getName();
    }

    /**
     * Get item value as string
     *
     * @return string
     */
    public function getValueString()
    {
        $value = $this->getValue();
        if (is_array($value)) {
            return implode(',', $value);
        }
        return $value;
    }
}