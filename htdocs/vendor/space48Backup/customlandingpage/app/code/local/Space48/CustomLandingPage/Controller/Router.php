<?php
/**
 * Space48
 *
 * Space48 Custom Landing Page module for magento, Allowing unique landing pages to be created
 * per attribute for a Magento store.
 *
 * @package Space48_Custom_Landing_Page
 */
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
class Space48_CustomLandingPage_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{
    /**
     * Initialise the observer for all routes passed to this controller.
     * @param $observer
     */
    public function initControllerRoutes($observer)
    {
        $front = $observer->getEvent()->getFront();
        $front->addRouter('customLandingPage', $this);
    }

    /**
     * Match URL Keys against the collection and either dispatch to the controller or
     * return false and allow magento to look for the next route observer
     *
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        $identifier = trim($request->getPathInfo(), '/');
        $brand = explode('/', $identifier, 3);
        $model = Mage::getModel('customlandingpage/brands')->getCollection()->addFilter('url_key',$brand[0]);

        if($model->getData()) {
            $request = Mage::app()->getRequest();
            $request->initForward()
                ->setControllerName('index')
                ->setModuleName('brands')
                ->setActionName('brand')
                ->setDispatched(false);
            return true;
        } else {
            return false;
        }

    }
}