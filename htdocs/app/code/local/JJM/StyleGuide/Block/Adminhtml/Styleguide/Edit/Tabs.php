<?php
class JJM_Styleguide_Block_Adminhtml_Styleguide_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("styleguide_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("styleguide")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("styleguide")->__("Item Information"),
				"title" => Mage::helper("styleguide")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("styleguide/adminhtml_styleguide_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
