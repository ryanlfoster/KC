<?php
class Space48_Brands_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
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
        $model = Mage::getModel('brands/brands')->getCollection()->addFilter('url_key',$brand[0]);

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