<?php
class JJM_Styleguide_Adminhtml_StyleguidebackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Manage Style Guide"));
	   $this->renderLayout();
    }
}