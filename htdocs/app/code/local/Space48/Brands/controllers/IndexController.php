<?php
class Space48_Brands_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * IndexAction
     *
     * Loads the collection where attributes are active,
     * Registers the values in the Magento Registry for use in other places.
     */
    public function indexAction()
    {
        $brands = Mage::getModel('brands/brands')
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

        $brandPage = Mage::getModel('brands/brands')
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