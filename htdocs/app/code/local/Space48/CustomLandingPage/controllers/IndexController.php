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
class Space48_CustomLandingPage_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * IndexAction
     *
     * Loads the collection where attributes are active,
     * Registers the values in the Magento Registry for use in other places.
     */
    public function indexAction()
    {
        $brands = Mage::getModel('customlandingpage/brands')
            ->getCollection()
            ->addFilter('status',1)
            ->setOrder('title','ASC')
            ->getData();

        Mage::register('brands', $brands);

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * BrandAction
     *
     * Gets the URL key and loads the collection based on this value.
     * Registers the values in the Magento Registry for use in other places.
     */
    public function brandAction()
    {
        $request = $this->getRequest();
        $identifier = trim($request->getPathInfo(), '/');
        $brand = explode('/', $identifier, 3);

        $brandPage = Mage::getModel('customlandingpage/brands')
            ->getCollection()
            ->addFilter('url_key', $brand[0])
            ->getData();

        Mage::register('brand' , $brandPage);

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__($brandPage[0]['meta_title']));
        $this->getLayout()->getBlock('head')->setDescription($brandPage[0]['meta_description']);
        $this->getLayout()->getBlock('head')->setKeywords($brandPage[0]['meta_keywords']);
        $this->renderLayout();
    }

}