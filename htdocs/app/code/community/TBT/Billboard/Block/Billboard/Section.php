<?php

class TBT_Billboard_Block_Billboard_Section extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_controller = 'tbtbillboard';
        $this->_blockGroup = 'tbtbillboard';
        $this->setTemplate("tbtbillboard/billboard/section.phtml");
    }
    
    protected function getHeading()
    {
        return $this->getData('heading');
    }
    
    protected function getContent()
    {
        return $this->getData('content');
    }
    
    protected function getImagePath()
    {
        return $this->getData('imagePath');
    }
}
