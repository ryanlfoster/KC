<?php
class JJM_Styleguide_CelebrityController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock("head")->setTitle($this->__("Style Guide"));
        $celeb = $this->getRequest()->getParams();
        $celeb = $celeb['name'];

        $celeb = Mage::getModel('styleguide/styleguide')->getCollection()->addFieldToFilter('name', $celeb)->getFirstItem();

        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("home", array(
            "label" => $this->__("Home Page"),
            "title" => $this->__("Home Page"),
            "link"  => Mage::getBaseUrl()
        ));

        $breadcrumbs->addCrumb("style guide", array(
            "label" => $this->__("Style Guide"),
            "title" => $this->__("Style Guide")
        ));


        $this->loadLayout();
        $this->getLayout()->getBlock('styleguide_celeb_index')->getCeleb($celeb);
        $this->renderLayout();
    }
}