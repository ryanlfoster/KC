<?php
class JJM_StyleGuide_Adminhtml_StyleGuideController extends Mage_Adminhtml_Controller_Action {
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}