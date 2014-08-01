<?php


class JJM_Styleguide_Block_Adminhtml_Styleguide extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_styleguide";
	$this->_blockGroup = "styleguide";
	$this->_headerText = Mage::helper("styleguide")->__("Styleguide Manager");
	$this->_addButtonLabel = Mage::helper("styleguide")->__("Add New Item");
	parent::__construct();
	
	}

}